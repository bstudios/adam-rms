<?php

$serverActions = [
  'ASSETS:EDIT:ANY_ASSET_TYPE' => [
    'Category' => 'Assets',
    'Table' => 'Assets',
    'Type' => 'Edit',
    'Detail' => 'Any asset type (including those with no instanceid)',
    'Dependencies' => null,
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
    'LEGACY-Description' => 'Edit any asset type - even those written by AdamRMS',
    'LEGACY-ID' => 19,
    'LEGACY-Sort Rank' => 16,
  ],
  'USERS:CREATE' => [
    'Category' => 'User Management',
    'Table' => 'Users',
    'Type' => 'Create',
    'Detail' => '',
    'Dependencies' => null,
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
    'LEGACY-Description' => 'Access a list of users',
    'LEGACY-ID' => 2,
    'LEGACY-Sort Rank' => 1,
  ],
  'USERS:EDIT' => [
    'Category' => 'User Management',
    'Table' => 'Users',
    'Type' => 'Edit',
    'Detail' => '',
    'Dependencies' => null,
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
    'LEGACY-Description' => 'Edit details about a user',
    'LEGACY-ID' => 5,
    'LEGACY-Sort Rank' => 2,
  ],
  'USERS:EDIT:THUMBNAIL' => [
    'Category' => 'User Management',
    'Table' => 'Users',
    'Type' => 'Edit',
    'Detail' => 'Thumbnail',
    'Dependencies' => ['USERS:EDIT'],
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
    'LEGACY-Description' => 'Set a user\'s thumbnail',
    'LEGACY-ID' => 14,
    'LEGACY-Sort Rank' => 11,
  ],
  'USERS:EDIT:NOTIFICATION_SETTINGS' => [
    'Category' => 'User Management',
    'Table' => 'Users',
    'Type' => 'Edit',
    'Detail' => 'Notification Settings',
    'Dependencies' => ['USERS:EDIT'],
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
    'LEGACY-Description' => 'Change another users notification settings',
    'LEGACY-ID' => 22,
    'LEGACY-Sort Rank' => 19,
  ],
  'USERS:EDIT:SUSPEND' => [
    'Category' => 'User Management',
    'Table' => 'Users',
    'Type' => 'Edit',
    'Detail' => 'Suspend',
    'Dependencies' => ['USERS:CREATE'],
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
    'LEGACY-Description' => 'Suspend a user',
    'LEGACY-ID' => 9,
    'LEGACY-Sort Rank' => 6,
  ],
  'USERS:DELETE' => [
    'Category' => 'User Management',
    'Table' => 'Users',
    'Type' => 'Delete',
    'Detail' => '',
    'Dependencies' => ['USERS:CREATE'],
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
    'LEGACY-Description' => 'Delete a user',
    'LEGACY-ID' => 15,
    'LEGACY-Sort Rank' => 12,
  ],
  'USERS:VIEW:OWN_POSITIONS' => [
    'Category' => 'Permissions Management',
    'Table' => 'Users',
    'Type' => 'View',
    'Detail' => 'Own Positions',
    'Dependencies' => null,
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
    'LEGACY-Description' => 'View own positions',
    'LEGACY-ID' => 16,
    'LEGACY-Sort Rank' => 13,
  ],
  'USERS:VIEW:MAILINGS' => [
    'Category' => 'User Management',
    'Table' => 'Users',
    'Type' => 'View',
    'Detail' => 'Mailings',
    'Dependencies' => ['USERS:CREATE'],
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
    'LEGACY-Description' => 'View mailing for a user',
    'LEGACY-ID' => 6,
    'LEGACY-Sort Rank' => 3,
  ],
  'USERS:VIEW_SITE_AS' => [
    'Category' => 'User Management',
    'Table' => 'Users',
    'Type' => '',
    'Detail' => 'View Site As',
    'Dependencies' => ['USERS:CREATE','USERS:EDIT','USERS:VIEW:MAILINGS','USERS:EDIT:SUSPEND'],
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
    'LEGACY-Description' => 'View site as a user',
    'LEGACY-ID' => 10,
    'LEGACY-Sort Rank' => 7,
  ],
  'INSTANCES:VIEW' => [
    'Category' => 'Instances',
    'Table' => 'Instances',
    'Type' => 'View',
    'Detail' => '',
    'Dependencies' => null,
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
    'LEGACY-Description' => 'Access a list of instances',
    'LEGACY-ID' => 20,
    'LEGACY-Sort Rank' => 17,
  ],
  'INSTANCES:CREATE' => [
    'Category' => 'Instances',
    'Table' => 'Instances',
    'Type' => 'Create',
    'Detail' => '',
    'Dependencies' => null,
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
    'LEGACY-Description' => 'Create a new instance',
    'LEGACY-ID' => 8,
    'LEGACY-Sort Rank' => 5,
  ],
  'INSTANCES:FULL_PERMISSIONS_IN_INSTANCE' => [
    'Category' => 'Instances',
    'Table' => 'Instances',
    'Type' => '',
    'Detail' => 'Full Permissions in Instance',
    'Dependencies' => ['INSTANCES:VIEW'],
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
    'LEGACY-Description' => 'Log in to any instance with full permissions',
    'LEGACY-ID' => 21,
    'LEGACY-Sort Rank' => 18,
  ],
  'INSTANCES:IMPORT:ASSETS' => [
    'Category' => 'Instances',
    'Table' => 'Instances',
    'Type' => 'Import',
    'Detail' => 'Import Assets to any Instance',
    'Dependencies' => ['INSTANCES:VIEW'],
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
  ],
  'INSTANCES:DELETE' => [
    'Category' => 'Instances',
    'Table' => 'Instances',
    'Type' => 'Delete',
    'Detail' => '',
    'Dependencies' => ['INSTANCES:VIEW'],
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
    'LEGACY-Description' => 'Delete Instance',
    'LEGACY-ID' => 23,
    'LEGACY-Sort Rank' => 20,
  ],
  'INSTANCES:PERMANENTLY_DELETE' => [
    'Category' => 'Instances',
    'Table' => 'Instances',
    'Type' => 'Permanently Delete',
    'Detail' => '',
    'Dependencies' => ['INSTANCES:DELETE'],
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
    'LEGACY-Description' => 'Permanently Delete Instance',
    'LEGACY-ID' => 24,
    'LEGACY-Sort Rank' => 21,
  ],
  'PERMISSIONS:VIEW' => [
    'Category' => 'Permissions Management',
    'Table' => 'Permissions',
    'Type' => 'View',
    'Detail' => '',
    'Dependencies' => null,
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
    'LEGACY-Description' => 'Access a list of permissions',
    'LEGACY-ID' => 11,
    'LEGACY-Sort Rank' => 8,
  ],
  'PERMISSIONS:EDIT' => [
    'Category' => 'Permissions Management',
    'Table' => 'Permissions',
    'Type' => 'Edit',
    'Detail' => '',
    'Dependencies' => null,
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
    'LEGACY-Description' => 'Edit list of permissions',
    'LEGACY-ID' => 12,
    'LEGACY-Sort Rank' => 9,
  ],
  'PERMISSIONS:EDIT:USER_POSITION' => [
    'Category' => 'Permissions Management',
    'Table' => 'Permissions',
    'Type' => 'Edit',
    'Detail' => 'User position',
    'Dependencies' => ['USERS:EDIT','USERS:VIEW:OWN_POSITIONS'],
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
    'LEGACY-Description' => 'Change a user\'s permissions',
    'LEGACY-ID' => 13,
    'LEGACY-Sort Rank' => 10,
  ],
  'VIEW-AUDIT-LOG' => [
    'Category' => 'General sys admin',
    'Table' => '',
    'Type' => '',
    'Detail' => '',
    'Dependencies' => null,
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
    'LEGACY-Description' => 'View audit log',
    'LEGACY-ID' => 7,
    'LEGACY-Sort Rank' => 4,
  ],
  'VIEW-ANALYTICS' => [
    'Category' => 'General sys admin',
    'Table' => '',
    'Type' => '',
    'Detail' => '',
    'Dependencies' => null,
    'Comment' => null,
    'Supported Token Types' => ["web-session"],
    'LEGACY-Description' => '',
    'LEGACY-ID' => null,
    'LEGACY-Sort Rank' => null,
  ],
  'USE-DEV' => [
    'Category' => 'Instances',
    'Table' => '',
    'Type' => '',
    'Detail' => '',
    'Dependencies' => null,
    'Comment' => null,
    'Supported Token Types' => ["web-session", "app-v2-magic-email"],
    'LEGACY-Description' => 'Use the Development Site',
    'LEGACY-ID' => 17,
    'LEGACY-Sort Rank' => 14,
  ],
];