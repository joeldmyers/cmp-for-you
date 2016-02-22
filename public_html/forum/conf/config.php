<?php if (!defined('APPLICATION')) exit();

// Conversations
$Configuration['Conversations']['Version'] = '2.2';
$Configuration['Conversations']['Conversation']['SpamCount'] = '2';
$Configuration['Conversations']['Conversation']['SpamTime'] = '30';
$Configuration['Conversations']['Conversation']['SpamLock'] = '60';
$Configuration['Conversations']['ConversationMessage']['SpamCount'] = '2';
$Configuration['Conversations']['ConversationMessage']['SpamTime'] = '30';
$Configuration['Conversations']['ConversationMessage']['SpamLock'] = '60';

// Database
$Configuration['Database']['Name'] = 'cmforyou_dsc4u';
$Configuration['Database']['Host'] = 'localhost';
$Configuration['Database']['User'] = 'cmforyou_dsc4u';
$Configuration['Database']['Password'] = 'Noida201301';

// EnabledApplications
$Configuration['EnabledApplications']['Conversations'] = 'conversations';
$Configuration['EnabledApplications']['Vanilla'] = 'vanilla';

// EnabledPlugins
$Configuration['EnabledPlugins']['GettingStarted'] = 'GettingStarted';
$Configuration['EnabledPlugins']['HtmLawed'] = 'HtmLawed';
$Configuration['EnabledPlugins']['cleditor'] = false;
$Configuration['EnabledPlugins']['ButtonBar'] = true;
$Configuration['EnabledPlugins']['Emotify'] = false;
$Configuration['EnabledPlugins']['FileUpload'] = false;
$Configuration['EnabledPlugins']['editor'] = true;
$Configuration['EnabledPlugins']['AllViewed'] = true;
$Configuration['EnabledPlugins']['IndexPhotos'] = true;
$Configuration['EnabledPlugins']['EmojiExtender'] = true;
$Configuration['EnabledPlugins']['Gravatar'] = true;
$Configuration['EnabledPlugins']['VanillaInThisDiscussion'] = true;
$Configuration['EnabledPlugins']['Quotes'] = true;
$Configuration['EnabledPlugins']['Tagging'] = true;
$Configuration['EnabledPlugins']['VanillaStats'] = true;
$Configuration['EnabledPlugins']['vanillicon'] = true;
$Configuration['EnabledPlugins']['StopForumSpam'] = true;
$Configuration['EnabledPlugins']['jsconnect'] = true;
$Configuration['EnabledPlugins']['SplitMerge'] = true;
$Configuration['EnabledPlugins']['GooglePrettify'] = true;

// Garden
$Configuration['Garden']['Title'] = 'Custom Med Patient Services';
$Configuration['Garden']['Cookie']['Salt'] = 'EFqKy9cNG45gEFg1';
$Configuration['Garden']['Cookie']['Domain'] = '';
$Configuration['Garden']['Registration']['ConfirmEmail'] = false;
$Configuration['Garden']['Registration']['Method'] = 'Connect';
$Configuration['Garden']['Registration']['CaptchaPrivateKey'] = '6LdVFRITAAAAAIOQE8K2l-DWxChmWpBELWyPoh_J';
$Configuration['Garden']['Registration']['CaptchaPublicKey'] = '6LdVFRITAAAAAIbICktFoz04vAjippmjFgcsGBUn';
$Configuration['Garden']['Registration']['InviteExpiration'] = '1 week';
$Configuration['Garden']['Registration']['InviteRoles']['3'] = '0';
$Configuration['Garden']['Registration']['InviteRoles']['4'] = '0';
$Configuration['Garden']['Registration']['InviteRoles']['8'] = '0';
$Configuration['Garden']['Registration']['InviteRoles']['16'] = '0';
$Configuration['Garden']['Registration']['InviteRoles']['32'] = '0';
$Configuration['Garden']['Email']['SupportName'] = 'CMP Forum';
$Configuration['Garden']['Email']['SupportAddress'] = 'info@cmpforyou.com';
$Configuration['Garden']['Email']['UseSmtp'] = false;
$Configuration['Garden']['Email']['SmtpHost'] = '';
$Configuration['Garden']['Email']['SmtpUser'] = '';
$Configuration['Garden']['Email']['SmtpPassword'] = '';
$Configuration['Garden']['Email']['SmtpPort'] = '25';
$Configuration['Garden']['Email']['SmtpSecurity'] = '';
$Configuration['Garden']['SystemUserID'] = '1';
$Configuration['Garden']['InputFormatter'] = 'Html';
$Configuration['Garden']['Version'] = '2.2';
$Configuration['Garden']['Cdns']['Disable'] = false;
$Configuration['Garden']['CanProcessImages'] = true;
$Configuration['Garden']['Installed'] = true;
$Configuration['Garden']['InstallationID'] = '0CB1-C8B6016D-D20B19F1';
$Configuration['Garden']['InstallationSecret'] = 'baeb6cd3a87256d7d42a7e40f8dd8e8e521a3173';
$Configuration['Garden']['HomepageTitle'] = 'Custom Med Patient Services';
$Configuration['Garden']['Description'] = '';
$Configuration['Garden']['Logo'] = 'M9AMI4U31X6L.jpg';
$Configuration['Garden']['RewriteUrls'] = true;
$Configuration['Garden']['Theme'] = 'bittersweet';
$Configuration['Garden']['EditContentTimeout'] = '3600';
$Configuration['Garden']['MobileInputFormatter'] = 'TextEx';
$Configuration['Garden']['AllowFileUploads'] = true;
$Configuration['Garden']['Messages']['Cache'] = array('[Base]');
$Configuration['Garden']['ShareImage'] = '6L579OHLDSLC.png';
$Configuration['Garden']['FavIcon'] = 'favicon_642c26410b5cba5a.ico';
$Configuration['Garden']['Search']['Mode'] = 'like';
$Configuration['Garden']['ForceSSL'] = TRUE;
$Configuration['Garden']['AllowSSL'] = TRUE;

// Plugins
$Configuration['Plugins']['GettingStarted']['Dashboard'] = '1';
$Configuration['Plugins']['GettingStarted']['Categories'] = '1';
$Configuration['Plugins']['GettingStarted']['Discussion'] = '1';
$Configuration['Plugins']['GettingStarted']['Plugins'] = '1';
$Configuration['Plugins']['GettingStarted']['Registration'] = '1';
$Configuration['Plugins']['editor']['ForceWysiwyg'] = false;
$Configuration['Plugins']['Vanillicon']['Type'] = 'v1';
$Configuration['Plugins']['StopForumSpam']['UserID'] = '7';

// Routes
$Configuration['Routes']['DefaultController'] = array('discussions', 'Internal');

// Vanilla
$Configuration['Vanilla']['Version'] = '2.2';
$Configuration['Vanilla']['Categories']['MaxDisplayDepth'] = '3';
$Configuration['Vanilla']['Categories']['DoHeadings'] = false;
$Configuration['Vanilla']['Categories']['HideModule'] = false;
$Configuration['Vanilla']['Categories']['Layout'] = 'modern';
$Configuration['Vanilla']['Discussions']['Layout'] = 'modern';
$Configuration['Vanilla']['Discussions']['PerPage'] = '30';
$Configuration['Vanilla']['Discussions']['SortField'] = 'd.DateLastComment';
$Configuration['Vanilla']['Discussion']['SpamCount'] = '3';
$Configuration['Vanilla']['Discussion']['SpamTime'] = '60';
$Configuration['Vanilla']['Discussion']['SpamLock'] = '120';
$Configuration['Vanilla']['Comment']['SpamCount'] = '5';
$Configuration['Vanilla']['Comment']['SpamTime'] = '60';
$Configuration['Vanilla']['Comment']['SpamLock'] = '120';
$Configuration['Vanilla']['Comment']['MaxLength'] = '8000';
$Configuration['Vanilla']['Comment']['MinLength'] = '6';
$Configuration['Vanilla']['Comments']['AutoRefresh'] = NULL;
$Configuration['Vanilla']['Comments']['PerPage'] = '30';
$Configuration['Vanilla']['Archive']['Date'] = '';
$Configuration['Vanilla']['Archive']['Exclude'] = false;
$Configuration['Vanilla']['AdminCheckboxes']['Use'] = true;

// Last edited by anu888 (107.201.107.23)2015-12-19 23:19:44