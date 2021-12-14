import { IonCard, IonCardContent, IonCardHeader, IonCardSubtitle, IonCardTitle, IonCol, IonImg, IonItem, IonLabel, IonList, IonRow, IonSlide, IonSlides, useIonViewWillLeave } from "@ionic/react";
import { useContext } from "react";
import { useParams } from "react-router";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { fileExtensionToIcon, formatSize, s3url } from "../../globals/functions";
import { faArrowRight, faBan, faFlag } from "@fortawesome/free-solid-svg-icons";
import { AssetTypeContext } from "../../contexts/Asset/AssetTypeContext";
import Page from "../../pages/Page";

const AssetType = () => {
    let { type } = useParams<{type: string}>();

    const { AssetTypes } = useContext(AssetTypeContext);
    
    //filter by requested asset type
    const thisAssetType = AssetTypes.find((element: IAssetType) => element.assetTypes_id == parseInt(type));

    //generate image carousel
    let images;
    if (thisAssetType.thumnails && thisAssetType.thumnails.length > 0) {
        images = <IonSlides>
                    {thisAssetType.thumbnails.map((image:any) => {
                        return (
                            <IonSlide>
                                <IonImg src={image.url} alt={image.s3files_name} />
                            </IonSlide>
                        )
                    })}
                </IonSlides>;
    }

    //Generate file list
    let files;
    if (thisAssetType.files && thisAssetType.files.length > 0){
        files = <IonCard>
                    <IonCardHeader>
                        <IonCardTitle>Asset Type Files</IonCardTitle>
                    </IonCardHeader>
                    <IonCardContent>
                        <IonList>
                            {thisAssetType.files.map(async (item :any) => {
                                return (
                                    <a href={ await s3url(item.s3files_id, item.s3files_meta_size)}>
                                        <IonItem key={item.s3files_id}>
                                            <IonLabel slot="start">
                                                <FontAwesomeIcon icon={fileExtensionToIcon(item.s3files_extension)} />
                                            </IonLabel>
                                            <IonLabel>
                                                <h2>{item.s3files_name}</h2>
                                            </IonLabel>
                                            <IonLabel slot="end">
                                                {formatSize(item.s3files_meta_size)}
                                            </IonLabel>
                                        </IonItem>
                                    </a>
                                )
                            })}
                        </IonList>
                    </IonCardContent>
                </IonCard>;
    }

    //return page layout
    return (
        <Page title={thisAssetType.assetTypes_name}>
            {images}
            <IonCard>
                <IonCardContent>
                    <IonRow>
                        <IonCol>
                            <div className="container">
                                <IonCardSubtitle>Manufacturer</IonCardSubtitle>
                                <IonCardTitle>{thisAssetType.manufacturers_name}</IonCardTitle>
                            </div>
                            <div className="container">
                                <IonCardSubtitle>Category</IonCardSubtitle>
                                <IonCardTitle>{thisAssetType.assetCategories_name}</IonCardTitle>
                            </div>
                            {thisAssetType.assetTypes_productLink && <div className="container">
                                <IonCardSubtitle>Product Link</IonCardSubtitle>
                                <IonCardTitle><a href={thisAssetType.assetTypes_productLink} target="_system">{thisAssetType.assetTypes_productLink}</a></IonCardTitle>
                            </div>}
                        </IonCol>
                        <IonCol>
                            <div className="container">
                                <IonCardSubtitle>Weight</IonCardSubtitle>
                                <IonCardTitle>{thisAssetType.assetTypes_mass_format}</IonCardTitle>
                            </div>
                            <div className="container">
                                <IonCardSubtitle>Value</IonCardSubtitle>
                                <IonCardTitle>{thisAssetType.assetTypes_value_format}</IonCardTitle>
                            </div>
                            <div className="container">
                                <IonCardSubtitle>Day Rate</IonCardSubtitle>
                                <IonCardTitle>{thisAssetType.assetTypes_dayRate_format}</IonCardTitle>
                            </div>
                            <div className="container">
                                <IonCardSubtitle>Week Rate</IonCardSubtitle>
                                <IonCardTitle>{thisAssetType.assetTypes_weekRate_format}</IonCardTitle>
                            </div>
                        </IonCol>
                    </IonRow>
                </IonCardContent>
            </IonCard>
            <IonCard>
                <IonCardHeader>
                    <IonCardTitle>Individual Assets</IonCardTitle>
                </IonCardHeader>
                <IonCardContent>
                    <IonList>
                        {thisAssetType.tags.map((item :any) => {
                            return (
                                <IonItem key={item.assets_id} routerLink={"/assets/" + thisAssetType.assetTypes_id + "/" + item.assets_id}>
                                    <IonLabel>
                                        <h2>{item.assets_tag}</h2>
                                    </IonLabel>
                                    <div className="container">{item.flagsblocks['COUNT']['BLOCK'] > 0 && <FontAwesomeIcon icon={faBan} color="#dc3545" />}</div>
                                    <div className="container">{item.flagsblocks['COUNT']['FLAG'] > 0 && <FontAwesomeIcon icon={faFlag} color="#ffc107" />}</div>
                                    <div slot="end">
                                        <FontAwesomeIcon icon={faArrowRight} />
                                    </div>
                                </IonItem>
                            )
                        })}
                    </IonList>
                </IonCardContent>
            </IonCard>
            {files}
        </Page>
    );
}

export default AssetType;