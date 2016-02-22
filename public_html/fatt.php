<? 
/*
FATT Version 2.1.1:
- Update GUI to align with marketing collateral and clean up UX
- Modified shell executions to occur within the selector
- Added lock file functionality and referencing
- Reworked initialization to better organize code and provide meaningful error messages
- Reworked call to pkill to operate off of the file owner instead of the database user
- Added in ini creation functionality
- Removed instances of fopen calls and changed to shell operations (such as echo > file for file generation)
- Added in auto-refresh upon success message
- Reworked Clear Transient function to remove WooCommerce sessions and flush Varnish cache
- Reworked Database Import selector to account for root and wp-content
*/
define("VERSION", '2.1.1');
ini_set('max_execution_time', 0); // Does not work if safe mode is enabled
ini_set('memory_limit', '192M');

/******************************************************
					Startup Class
					-------------
- Manages the display of the menu and performs startup tasks
	- Start Sessions
	- Restrict Script Access based on SQL Backup Status
******************************************************/

class get_started{
	public $display;		// Display object
	private $db_exists;		// See if the database is empty
	public $db_output;		// Output for the database check
	
	public function __construct(){
		session_start();
		
		// Regenerate session to prevent session hijacking (implement tokens later to prevent session fixation)
		session_regenerate_id(true);
		
		// Session initialization for tarball and sql_backup
		if((!isset($_SESSION['sql_backup']))||(empty($_SESSION['sql_backup'])))
			$_SESSION['sql_backup'] = 'None';
			
		if((!isset($_SESSION['tarball']))||(empty($_SESSION['tarball'])))
			$_SESSION['tarball'] = 'None';
		
		// Returns true or string on error
		$started = $this->initialize();
		
		// Create the display object
		$this->display = new display();
		
		// We were able to initialize config parsing, so check database and load the menu
		if($started === true){
			$this->check_database();
		}else{
			$started = $this->display->set_error_box($started);
		}
		$this->display->msg = $started;
	}
	
	// Performs all of the initialization
	private function initialize(){
		// Check to see if this is Linux or Windows based on shared library (no powershell support yet)
		if(PHP_SHLIB_SUFFIX == 'dll'){
			return '<h1>FATT does not currently support Windows</h1>';
		}
		
		// Check if shell_exec is disabled
		if(!is_callable('shell_exec')||(strpos(ini_get('disable_functions'), 'shell_exec') === true)){
			return '<h1>Shell_exec is not enabled! Cannot parse config file!</h1><h2>Please manually remove FATT.</h1>';
		}
		
		// Check if PDO is installed / enabled
		if(!class_exists('PDO')){
			return '<h1>PDO is not installed!</h1><p>We will eventually add in fallbacks to MySQLi and regular MySQL if necessary</p>';
		}
		
		// Check for fattlock file and see what step it is on
		$lock = dirname(__FILE__).'/fattlock';
		if(file_exists($lock)){
			$stage = shell_exec('cat '.$lock.' 2>&1');
			return '<h1>FATT IS WORKING BEHIND THE SCENES!</h1><h2>Current Step: '.$stage.'</h1>';
		}
		
		// Check for wp-config.php
		if(file_exists('wp-config.php')){
			// Grab all of the database information from the wp-config file
			$config['DB_NAME'] = shell_exec("cat wp-config.php | grep DB_NAME | cut -d \' -f 4");
			$config['DB_USER'] = shell_exec("cat wp-config.php | grep DB_USER | cut -d \' -f 4");
			$config['DB_PASSWORD'] = shell_exec("cat wp-config.php | grep DB_PASSWORD | cut -d \' -f 4");
			$config['DB_HOST'] = shell_exec("cat wp-config.php | grep DB_HOST | cut -d \' -f 4");
			$config['DB_PORT'] = '';
			$config['PREFIX'] = shell_exec("cat wp-config.php | grep table_prefix | cut -d \' -f2");
			$config['WP_VER'] = shell_exec("cat wp-includes/version.php | grep \"wp_version =\" | cut -d\' -f 2");
			
			// Parse the port for the connection string if necessary; otherwise default to 3306
			if(strpos($config['DB_HOST'], ':') !== false){
				$config['DB_PORT'] = str_replace(':', '', substr($config['DB_HOST'], strpos($config['DB_HOST'], ':')));
				$config['DB_HOST'] = substr($config['DB_HOST'], 0, strpos($config['DB_HOST'], ':'));
			}else{
				$config['DB_PORT'] = 3306;
			}
			
			//Find the current web node			
			$wnode = gethostname();
			$wnode = substr($wnode, 0, strpos($wnode, "."));
			$config['W_NODE'] = $wnode;
			
			//Find the XID
			$user = shell_exec("ls -ld ".__FILE__." | awk '{print \$3}'");
			$xid = substr($user, 4, 7);
			$config['XID'] = $xid;
			
			// Crude way of setting the site URL for now
			if((isset($_SERVER['HTTPS']))&&(!empty($_SERVER['HTTPS'])))
				$config['SITE_URL'] = 'https://';
			else
				$config['SITE_URL'] = 'http://';
				
			$config['SITE_URL'] .= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$config['SITE_URL'] = str_replace(basename($_SERVER['PHP_SELF']), '', $config['SITE_URL']);
			$config['SITE_URL'] = substr($config['SITE_URL'], 0, -1);
			$config['FILEOWNER'] = shell_exec("ls -ld ".__FILE__." | awk '{print \$3}'");
			
			// Create Globals for each of the database variables
			foreach($config as $k => $v){
				// Remove any unwanted spaces
				$v = trim($v);
				// Check for only critical WordPress data
				if(empty($v) && ($k !== 'DB_PORT') && ($k !== 'WP_VER')){
					return '<h1>Error: Check wp-config.php file for empty '.$k.'!</h1>';
				}
				define($k, $v);
			}
			
			return true;
		}else{
			return '<h1>We could not find the wp-config.php file!</h1><p>Please be sure to drop this script into the directory where the wp-config.php file lives.</p>';
		}
	}
	
	public function check_database(){
		// Check to see if there is database data by attempting to get the current site URL
		$check_db = new fix_things();
		$sql_result = $check_db->table_check();
		
		// If the database is not empty, set the form for Create Backup and allow Fix Things to display
		if($sql_result !== false){
			$this->db_exists = true;
			$this->db_output = '<h3>Create Backup</h3>
								<p>Create a new backup File</p>
								<div class="row">
									<div class="col-sm-12">
										<form id="create_backup" name="create_backup" role="form" method="post">
											<input type="hidden" name="selector" value="create_backup">
											<button type="submit" class="btn btn-default">CREATE BACKUP</button>
										</form>
									</div>
								</div>';
			
		// Else display the warning for the database being empty and force them to import
		}else{
			$this->db_exists = false;
			$this->db_output = $this->display->set_error_box('<p>Database is missing critical data!</p><p class="small"><i>Check to make sure that there is a database and that it is a WordPress application!</i></p><p>It Might be missing tables and/or other critical WordPress data!</p>');
		}
	}
}

/************************************************************
					Display Class
					-------------
- Manages the display for error messages and success messages for AJAX requests.
- Mainly does the formatting of text.
************************************************************/
class display{
	public $msg = '';
	
	public function set_warning_txt($msg){
		return $this->msg = '<p class="text-warning">'.$msg.'</p>';
	}
	
	public function set_warning_box($msg){
		return $this->msg = '<div class="alert alert-warning" role="alert"><b>'.$msg.'</b></div>';
	}
	
	// Error Selections
	public function set_error_txt($msg){
		return $this->msg = '<p class="text-danger">'.$msg.'</p>';
	}
	
	public function set_error_box($msg){
		return $this->msg = '<div class="alert alert-danger" role="alert"><b>'.$msg.'</b></div>';
	}
	
	// Success Selections
	public function set_success_txt($msg){
		return $this->msg = '<p class="text-success"><b>'.$msg.'</b></p>';
	}
	
	public function set_success_box($msg){
		return $this->msg = '<div class="alert alert-success" role="alert">'.$msg.'</div>';
	}
}

/******************************************************
					Selector Class
					--------------
- Acts as the Controller for AJAX requests
	- create_backup		->	Create a backup for the database
	- select_sql_backup	->	Locates and lists MySQL backups found in FATT's immediate directory
	- restore_backup	-> 	Executes the restore for the database with a success/failure response
	- create_archive	->	Backups up database and packs into tarball with wp-content
	- select_archive	->	Locates and lists archives available to unpack
	- unpack_archive	->	Unpacks the archive based on its prefix and file extension
	- get_url			-> 	Gets the current URL on the database based on current prefix
	- set_url			->	Set the URL for the WordPress Application
	- get_prefixes		->	Gets the current prefix(es) from the database
	- set_prefixes		->	Update the prefix on the database tables
	- killFatt			-> 	Removes the FATT script
	- killProcesses		->	Runs pkill on processes based on who owns FATT
	- killTransient		->	Removes transient data from database - v2.0.2 adds WooCommerce and Varnish clear. 
	- cleanup			->	Removes any traces of FATT (archives, MySQL Backups, etc)
******************************************************/
	class selector {
		public $fix = '';	// fix_things class
		public $display;	// display object
		public $archive;	// archive class
		public $mySQL;		// MySQL class
		private $lock;		// lock class
		
		public function __construct($selection = ''){
			$this->fix = new fix_things();
			$this->display = new display();
			$this->mySQL = new mysqlStuffs();
			$this->archive = new fattArchive($this->fix, $this->mySQL);
			$this->lock = new lockFile();
			
			if(!empty($selection)){
								
				// Pretty much just for select/backup/restore MySQL
				if(($selection !== 'fix_things')){
					$this->$selection();
				
				// Route actions that require the script to die
				}else{
					$response = '';
					foreach($_POST as $k => $v){
						if(($k !== 'selector')&&($v !== 'false')&&(is_callable($this->$k()))){
							$response .= $this->$k();
						}
					}
					
					$response .= $this->display->set_warning_box('<p>FATT has cleaned up and removed itself.</p>');
					
					echo $response;
					
					$this->killFatt();
				}
			}
		}
		
		/** MySQL Backups **/
		
		public function create_backup(){
			// Generate the command
			if(($file = $this->mySQL->create_sql_backup()) !== false){
				// No lock is going to exist for this flat option, so create lock
				$cmd = 	$this->lock->updateLock('Backing up MySQL database').
						$this->mySQL->cmd.
						$this->lock->removeLock();
				
				shell_exec($cmd);
				echo $this->display->set_success_box('<p>MySQL Backup created: '.$file.'</p>');
			}else{
				echo $this->display->set_error_box('<p>Something went terribly wrong while trying to create the backup!</p>');
			}
		}
		
		public function select_sql_backup(){
			$response = '';
			
			$file_list = $this->mySQL->select_sql_backup();
			if(!empty($file_list)){
				if(isset($_SESSION['sql_backup'])){
					$response = $this->display->set_success_txt('Last backup file created this session: '. $_SESSION['sql_backup']);
				}
				$response .= '
				<form role="form" class="form-horizontal" method="post">
					<div class="form-group">
						<label for="backup_file" class="col-sm-4 control-label">Select Backup File:</label>
						<div class="col-sm-8">
							<select class="form-control" name="backup_file">';
				foreach($file_list as $k=>$v){
					$response .= '<option value="'.$v.'"selected>'.$v.'</option>';
				}
				
				$response .= '
							</select>
						</div>
					</div>
					<input type="hidden" name="selector" value="restore_backup">
					<div class="col-sm-12">
						<button type="submit" class="btn btn-default pull-right">RESTORE BACKUP</button>
					</div>
				</form>';
			}else{
				$response = $this->display->set_warning_box('<p>NO SQL FILES WERE FOUND!</p>');
			}
			
			echo $response;
		}
		
		public function restore_backup(){
			// Generate the command
			if($this->mySQL->restore_sql_backup($_POST['backup_file']) !== false){
				// No lock is going to exist for this flat option, so create lock
				$cmd = 	$this->lock->updateLock('Restoring MySQL database').
						$this->mySQL->cmd.
						$this->lock->removeLock();
				
				// shell_exec will only return NOT empty on error
				shell_exec($cmd);
				echo $this->display->set_success_box('<p>Restored the MySQL database from '.$_POST['backup_file'].' successfully!</p>');
			}else{
				echo $this->display->set_error_box('<p>Something went horribly wrong!</p><p>We were not able to restore the database from file '.$_POST['backup_file'].'!</p>');
			}
		}
		
		/** Archives **/
		
		public function create_archive(){
			if($this->mySQL->create_sql_backup('./wp-content') !== false){
				$file = $this->archive->create_archive();
				$_SESSION['tarball'] = $file;
				// Put together the commands for locks, mysql, and tarball
				$cmd = 	$this->lock->updateLock('Backing up MySQL database').
						$this->mySQL->cmd.
						$this->lock->updateLock('Creating tarball for transport').
						$this->archive->cmd.
						$this->lock->removeLock();
				
				shell_exec($cmd);
				$response = $this->display->set_success_box('Archive completed: '.$file);
			}else{
				$response = $this->display->set_error_box('<p>We were unable to back up the MySQL database!</p><p>MySQL backup and file archive for this process were aborted!</p>');
			}
			
			echo $response;
		}
		
		public function select_archive(){
			$disabled = '';		// Disables the button if no files are found
			$response = '';
			$archive_list = $this->archive->select_archive();
			if(!empty($archive_list)){
				$response = '
				<form role="form" method="post" class="form-horizontal">
					<div class="form-group">
						<label for="archive_file" class="col-sm-3 control-label">Select Archive:</label>
						<div class="col-sm-8">
							<select name="archive_file" class="form-control">
								<option selected disabled>-- Choose an Archive --</option>';
				foreach($archive_list as $k=>$v){
					$response .= '<option value="'.$v.'">'.$v.'</option>';
				}
				
				$response .= '
							</select>
						</div>
					</div>
					<input type="hidden" name="selector" value="unpack_archive">
					<div class="col-sm-12">
						<button type="submit" class="btn btn-default pull-right">UNPACK ARCHIVE</button>
					</div>
				</form>';
			}else{
				$response = $this->display->set_warning_box('<p>Unable to locate any archives.</p>');
			}
			
			echo $response;
		}
		
		public function unpack_archive(){
			// Appears checkboxes equal on if checked; else not set at all
			$import = (isset($_POST['import']) ? true : false);
			$automagic = (isset($_POST['automagic']) ? true : false);
			$archive_file = (isset($_POST['archive_file']) ? $_POST['archive_file'] : false);
			$response = '';
			
			if($archive_file !== false){
				// Construct the command and verify checks for importing pass
				if($this->archive->unpack_all_archives($_POST['archive_file'], $import) !== false){
					$cmd = $this->lock->updateLock('Unpacking file archive').
							$this->archive->cmd;
					
					$reponse = '<p>During this processes we have:</p><ul class="list-unstyled">
									<li>- Extracted the wp-content folder</li>';
					
					// Append the MySQL import if import is selected and we can find a FATT SQL file
					if($import){
						$backup = $this->mySQL->locate_fatt_sql();
						if($backup !== false){
							$this->mySQL->restore_sql_backup($backup);
							$cmd .= $this->lock->updateLock('Restoring MySQL database').
									$this->mySQL->cmd;
							$response .= '<li>- Imported the MySQL database</li>';
						}
					}
					
					// If database updates is selected, append commands
					if($automagic){
						$cmd .= $this->lock->updateLock('Applying fixes to the database');
						$response .= '<li>- Updated table prefixes, usermeta, and options</li>';
					}
					
					$cmd .= $this->lock->removeLock();
					shell_exec($cmd);
					
					$response .= '</ul>';
					$response = $this->display->set_success_box($response);
					
				// We cannot automagically restore this archive
				}else{
					$response = $this->display->set_error_box('<p>We are having issues automagically unpacking the things!</p><p>You may wish to manually do this, or repack the archive on the source</p>');
				}
			// Archive file was not selected
			}else{
				$response = $this->display->set_error_box('<p>You need to select an archive!</p>');
			}
			
			echo $response;
		}
		
		// Create a new INI file
		public function createINI(){
			$phpini = new iniGenerator($_POST);
			if($phpini->generate() !== false){
				$this->killProcesses();
				$response = $this->display->set_success_box('<p>'.$_POST['ini_select'].' successfully created and processes restarted!</p>');
			}else{
				$response = $this->display->set_error_box('<p>Something went wrong! The new INI is not created!</p>');
			}
			
			echo $response;
		}
		
		public function get_url(){
			$result = $this->fix->get_url();
			if($result !== false){
				$response = '<span class="glyphicon glyphicon-globe glyphicon-size-massive" aria-hidden="true"></span>
							<p class="small">Old: <i>'.$result.'</i></p>
							<p class="small">New: <i>'.SITE_URL.'</i></p>
							<input type="hidden" name="set_url" value="false" />';
			
			}else{
				$response = $this->display->set_error_txt('Could not locate the site URL!');
			}
			return $response;
		}
		
		public function set_url(){
			$old_url = $this->fix->get_url();
			$this->fix->set_url();
			$response = $this->display->set_success_box('<p><b>Site URL Updated!</b></p><p>Old Site URL: '.$old_url.'</p><p>Site URL is '.SITE_URL.'</p>');

			echo $response;
			
		}
		
		// Get the prefix list
		public function get_prefixes(){
			// Only allow the option for updating prefix to be available if it can determine the table prefix based on usermeta
			if($this->fix->get_prefix_list() !== false){
				$response = '<span class="glyphicon glyphicon-th-list glyphicon-size-massive" aria-hidden="true"></span>
							<p class="small">Old: <i>'.$this->fix->old_prefix.'</i></p>
							<p class="small">New: <i>'.PREFIX.'</i></p>
							<input type="hidden" name="set_prefixes" value="false" />';
			// Otherwise display an error box
			}else{
				$response = $this->display->set_error_box('<p>Could not parse prefixes!</p><p>This function will not run even if selected until there is only one COMPLETE WordPress database present</p>'); 
			}
			
			return $response;
		}
		
		// Set prefixes
		public function set_prefixes(){
			if($this->fix->rename_tables())
				$response = $this->display->set_success_box('<p><b>Table Prefixes Updated!</b></p><p>Prefix "'.$this->fix->old_prefix.'" updated to "'.$this->fix->new_prefix.'" on all tables and database entries</p>');
			else
				$response = $this->display->set_error_box('<p>Multiple prefixes were detected! No queries were ran!</p><p>Check the database for multiple usermeta tables</p>');
			
			echo $response;
		}
	
		// Auto-destruct the script
		private function killFatt(){
			// Bash clean up assigned to variables
			$killsql = "find * -type f -name 'FATT-SQL_*' -mtime +3 -exec rm {} \;";
			$killarch = "find * -type f -name 'FATT-archive_*' -mtime +3 -exec rm {} \;";
			
			// Establish the path to the script
			$path = __FILE__;
			
			// Absolute path to SQL backup
			if(isset($_SESSION['sql_backup'])&&!empty($_SESSION['sql_backup'])){
				$sql_backup = dirname($path).$_SESSION['sql_backup'];
			}
			// Run cleanup
			shell_exec($killsql);
			shell_exec($killarch);
			// Remove FATT
			shell_exec('rm -f '.$path);
		}
		
		// Stop all processes by user
		private function killProcesses(){
			shell_exec('pkill -U '.FILEOWNER);
			echo $this->display->set_success_box('Processes ran by '.FILEOWNER.' terminated!');
		}
		
		// Clear the transient data from the database
		public function killTransient(){
			if($this->fix->get_prefix_list() !== false){
				$this->mySQL->killTransient($this->fix->old_prefix);
				// Put together the commands for lock and mysql
				$cmd = 	$this->lock->updateLock('Clearing cache from database and varnish').
						$this->mySQL->cmd.
						$this->lock->removeLock();
				shell_exec($cmd);
				
				$response = $this->display->set_success_box('Transient Data, WC Sessions and Varnish cleared successfully!');
			}else{
				$response = $this->display->set_error_box('Something went terribly wrong with clearing the transient data!');
			}
			
			echo $response;
		}
		
		// Cleans up any / all files related to FATT, including the archives and the MySQL dumps
		private function cleanup($directory = '.'){
			$remove = '';
			if ($dh = opendir($directory)) {
				while (false !== ($file = readdir($dh)) ){
					// If this appears to be one of the archive remnants, then remove it
					if((strpos($file, 'FATT-archive') !== false)||(strpos($file, 'FATT-SQL') !== false)){
						$remove .= 'rm -f '.$file.';';
					}
				}
				closedir($dh);
			}
			
			return $remove;
		}
	}
/******************************************************
					LockFile Class
					--------------
- Returns the commands necessary for locks on certain processes
	- updateLock	->	Updates the lock file with process name
	- removeLock	->	Removes the lock file
******************************************************/
	class lockFile {
		private $lockfile = '';
		
		public function __construct(){
			$this->lockfile = dirname(__FILE__).'/fattlock';
		}
		
		public function updateLock($process = ''){
			if(!empty($process)){
				return 'echo "'.$process.'" > '.$this->lockfile.';';
			}
		}
		
		public function removeLock(){
			return 'rm -f '.$this->lockfile.';';
		}
	}
	
/******************************************************
					iniGenerator Class
					------------------
- Creates an ini file after backing up a pre-existing ini if necessary
	- generate		->	Create the appropriate INI based on constructor
	- backupINI		->	Backup active INI file as *.ini-backup
******************************************************/
	class iniGenerator{
		private $phpconfig = array();
		public $filename = '';
		
		// Assemble the data
		public function __construct($data){
			$this->filename = dirname(__FILE__).'/'.$data['ini_select'];
			
			foreach($data as $k => $v){
				// Construct the keys based on the prefix
				if(strpos($k, 'php_') !== false){
					$key = substr($k, 4);
					$this->phpconfig[$key] = $v;
				}
			}
		}
		
		// Create the INI
		public function generate(){
			if($this->backupINI()){
				$content = '';
				
				foreach($this->phpconfig as $k => $v){
					$content .= 'echo "'.$k.' = '.$v.'" >> '.$this->filename.';';
				}
				
				shell_exec('touch '.$this->filename.' && '.$content);
				if(file_exists($this->filename)){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		
		private function backupINI(){
			// Backup any pre-existing ini files
			if(file_exists($this->filename)){
				if(rename($this->filename, $this->filename.'-backup')){
					return true;
				}else{
					return false;
				}
			// No backup necessary
			}else{
				return true;
			}
		}
	}
	
/******************************************************
					fattArchive Class
					-----------------
- Manages connections to the database and sanitizes input
	__construct(object, object)
	select_archive()
	create_archive()
	unpack_all_archives(string, bool)
******************************************************/
	class fattArchive{
		private $fix;		// Fix the things object
		private $mySQL;		// MySQL object
		public $cmd;		// Command generated by create / restore
		public $lock;		// Lock class
		
		public function __construct($fix = '', $mySQL = ''){
			$this->fix = (empty($fix) ? false: $fix);
			$this->mySQL = (empty($mySQL) ? false : $mySQL);
			
			if((!$this->fix)||(!$this->mySQL))
				die('Something went horribly wrong in the FATT archives!');
		}
		
		/*
		select_archive()
		--------------
		- Retrieves a list of archives within FATT's immediate directory
		--------------
		*/
		public function select_archive(){
			$directory = dirname(__FILE__);
			$archive_list = array();
			$allowed = array(
				'.tar',
				'.zip',
				'.gz',
				'.tar',
				'.bz2'
			);
			
			// Time to open the directory and look for archives!
			if(is_dir($directory)){
				if ($dh = opendir($directory)) {
					while (false !== ($file = readdir($dh)) ){
						foreach($allowed as $check){
							if(!in_array($file, $archive_list)){
								if((strpos($file, $check) !== false)&&(strpos($file, '.sql') === false)){
									$archive_list[] = $file;
								}
							}
						} // END FOREACH
					}
					closedir($dh);
				}
			}
			return $archive_list;
		}

		/*
		create_archive()
		--------------
		- Create the MySQL backup in wp-content/ then tarball the dir
		--------------
		*/
		public function create_archive(){
			$time = date("_His_mdY");
			$filename = 'FATT-archive_'.$time.'.tar.gz';
			// Fire off a MySQL backup in wp-content/
			$this->cmd = 'tar -zcf '.$filename.' ./wp-content/;';
			
			return $filename;
		}
		
		/*
			unpack_all_archives(string)
			--------------
			- Extracts an archive of content
			--------------
		*/
		public function unpack_all_archives($archive_backup = '', $import){
			
			// Break the backup out by . which determines how many . there are
			$match = explode('.', $archive_backup);
			$numFound = count($match);
			$ext = array();
			$cmd = false;
			
			// Determine potential file extensions / alternate variation
			switch(true){
				default:
				case ($numFound <= 1):
					break;
				// One potential found
				case ($numFound == 2):
					$ext[0] = '.'.$match[1];
					break;
				// One - many potential extensions found, so set extAlt
				case ($numFound <= 3):
					$ext[0] = '.'.$match[$numFound - 1];
					$ext[1] = '.'.$match[$numFound - 2].$ext[0];
					// Reverse the array to test with the combination first
					$ext = array_reverse($ext);
					break;
			}
			
			// File was not made by FATT - check other archive types
			if(!empty($ext)){
				// Loop through to check for an extension match
				foreach($ext as $extension){
					if(!$cmd){
						// Perform the necessary BASH commands to extract data 
						switch($extension){
							case '.tar.gz':
							case '.tgz':
								$cmd = 'tar -xzf ';
								break;
							case '.gz':
								$cmd = 'gunzip -d < ';
								break;
							case '.zip':
								$cmd = 'unzip ';
								break;
							case '.tar':
								$cmd = 'tar -xf ';
								break;
							case '.bz2':
								$cmd = 'bunzip2 ';
								break;
						}
					}
				}// END FOREACH
			}
			
			if($cmd){
				// Extract the archive
				$this->cmd .=  $cmd.$archive_backup.';';
				return true;
			}else{
				return false;
			}
		}
	}
	
/******************************************************
					Database Class
					--------------
- Manages connections to the database for updates
	- connect()
	- run(string)		->	Run a query that fetches single result
	- runAll(string)	->	Run a query that fetches ALL results
	- disconnect()
******************************************************/
	class wpaas_connection {
		public $pdo;				// PDO connection
		public $query;				// MySQL query
		private $bind = array();	// Used for binding params
		protected $table;			// Table to update
		
		public function __construct(){}
		
		public function __destruct(){
			if($this->pdo !== null)
				$this->disconnect();
		}
		
		public function connect(){
			$dsn = 'mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME;
			try{
				$this->pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
			}catch(PDOException $e){
				die('FIX YOUR CONFIG FILE! Connection failed: '.$e->getMessage());
			}
		}
	
		public function run($sql = ''){
			$this->connect();
			$query = $this->pdo->prepare($sql);
			$result = $query->execute();
			$result = $query->fetch(PDO::FETCH_ASSOC);
			
			return $result;
		}
		
		public function runAll($sql = ''){
			$this->connect();
			$query = $this->pdo->prepare($sql);
			$result = $query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			
			return $result;
		}
		
		public function disconnect(){
			$this->pdo = null;
		}	
	}

/******************************************************
					mysqlStuffs Class
					--------------
- Performs the actions for all MySQL database interactions
regarding backups (select, create, restore)
******************************************************/
	class mysqlStuffs{
		public $mysqlBackup = '';
		public $cmd = '';
		
		/*
		create_sql_backup()
		--------------
		Create the SQL backup as a GZip backup with _date_time stamp
		
		Returns filename on success or false on failure
		--------------
		*/
		public function create_sql_backup($dir = ''){
			$time = date("_His_mdY");
			// Create the backup based on the name of the database and time of the backup
			$filename = 'FATT-SQL_'.DB_NAME.$time.'.sql.gz';
			
			$this->cmd = "mysqldump -h ".DB_HOST." -u ".DB_USER." -p'".DB_PASSWORD."' -P ".DB_PORT." ".DB_NAME." | gzip > ";
			
			// If the directory isn't empty, then this is a tarball. Format the directory if it exists.
			if(!empty($dir)){
				$dir = $this->formatDir($dir);
				if($dir !== false){
					$filename = $dir.'/'.$filename;
				}else{
					return false;
				}
			}else{
				$_SESSION['sql_backup'] = $filename;
			}
			
			$this->cmd .= $filename.';';
			
			return $filename;
		}
		
		/*
			select_sql_backup()
			--------------
			Extracts list of SQL files in current directory, including gz and zip
			--------------
		*/
		public function select_sql_backup($directory = ''){
			if(empty($directory))
				$directory = dirname(__FILE__);
				$contdir = dirname(__FILE__) .'/wp-content/';
			
			$file_list = array();
			$contsql = array();
			// Time to open the directory and look for SQL backups!
			if(is_dir($directory)){
				// Check current directory
				if ($dh = opendir($directory)) {
					while (false !== ($file = readdir($dh)) ){
						if(strpos($file, '.sql'))
							$file_list[] = $file;
					}
					closedir($dh);
				}
				
			}
			// Time to open the wp-content directory and look for MOAR SQL backups!
			/* if(is_dir($contdir)){
				// Check current directory
				if ($cdh = opendir($contdir)) {
					while (false !== ($file = readdir($cdh)) ){
						if(strpos($file, '.sql'))
							$contsql[] = $file;
					}
					closedir($cdh);
				}
				
			}
			$fileResults = array_merge($file_list, $contsql); */
			return $file_list;
		}
		
		/*
			restore_sql_backup(string)
			--------------
			- Extracts the backup to a temporary file if necessary
			- Imports the database backup
			- Removes the temporary file if created
			--------------
		*/
		public function restore_sql_backup($sql_backup){
			$precmd = '';
			
			// Check to see if the file contains .sql; otherwise it is the .tar.gz outlier
			$sql_check =  strpos($sql_backup, '.sql');
			if(!$sql_check){
				$ext = '.tar.gz';
			}else{
				$ext = substr($sql_backup, $sql_check);
			}
			
			// Staged SQL file that will be removed
			$base_name = str_replace($ext, '', $sql_backup).'_fatt.sql';
			
			// Drop all tables first and then import the database
			$auth = "mysql -h ".DB_HOST." -u ".DB_USER." -p'".DB_PASSWORD."' -P ".DB_PORT." ".DB_NAME;
			
			$drop = "SET FOREIGN_KEY_CHECKS = 0; SET @tables = NULL; SELECT GROUP_CONCAT(table_schema, '.', table_name) INTO @tables FROM information_schema.tables WHERE table_schema = '".DB_NAME."'; SET @tables = CONCAT('DROP TABLE IF EXISTS ', @tables); PREPARE stmt FROM @tables; EXECUTE stmt; DEALLOCATE PREPARE stmt; SET FOREIGN_KEY_CHECKS = 1;";
			$cmd =  $auth."< ./";
			
			// File is archive, so we should extract it first
			if($ext !== '.sql'){
				// Perform the necessary BASH commands to extract data if necessary
				switch($ext){
					case '.sql.tgz':
						$precmd = '';
						break;
					case '.sql.gz':
						$precmd = "gunzip -dc < ".$sql_backup;
						break;
					case '.sql.zip':
						$precmd = 'unzip -p '.$sql_backup;
						break;
					case '.tar.gz':
						$precmd = 'tar -xzOf '.$sql_backup;
						break;
				}
				
				// Automatically comment out CREATE DATABASE and USE lines if present, then extract output to new .sql file.
				$precmd .= " | sed 's/CREATE DATABASE/-- CREATE DATABASE/g' | sed 's/USE/-- USE/g' >> ./".$base_name.' ; ';
			
			// Regular SQL file
			}else{
				$precmd = "sed 's/CREATE DATABASE/-- CREATE DATABASE/g' ./".$sql_backup." | sed 's/USE/-- USE/g' >> ./".$base_name.' ; ';
			}
			
			// Remove the unnecessary file
			$postcmd = '; rm -f ./'.$base_name.';';
						
			$this->cmd = $auth.' -e "'.$drop.'" ; '.$precmd.$cmd.$base_name.$postcmd;
		}
		/* 
			Clears the transient data from the options table in the Presses of the Word
			Defaults to the prefix found in the wp_config file
		*/
		public function killTransient($prefix = PREFIX){
			$auth = "mysql -h ".DB_HOST." -u ".DB_USER." -p'".DB_PASSWORD."' -P ".DB_PORT." ".DB_NAME;
			$delete = ' -e "DELETE FROM '.$prefix."options WHERE option_name LIKE '%transient%'; OPTIMIZE TABLE ".$prefix.'options;';
			$wc = ' DELETE FROM '.$prefix."options WHERE option_name LIKE '%wc_session%'; OPTIMIZE TABLE ".$prefix.'options";';
	
			$this->cmd = $auth.$delete.$wc;

			//Pulling the domain from the request
			$fdom = $_SERVER['HTTP_HOST'];
			//form the command to clear the cache using the domain from above
			$xbdom = "curl -XBAN $fdom";
			//executes the command now that it is formed - this will clear varnish
			shell_exec($xbdom);
			
			}
		
		// Find the FATT MySQL .sql.gz in wp-content and return the backup name
		public function locate_fatt_sql(){
			$needle = 'FATT-SQL_';
			$directory = 'wp-content';
			$sql_backup = '';
			
			// Check for the MySQL backup within wp-content
			if(is_dir($directory)){
				if($dh = opendir($directory)){
					while(false !==($file = readdir($dh))){
						if((strpos($file, $needle) !== false)&&(empty($sql_backup))){
							return 'wp-content/'.$file;
						}
					}
				}
			}
			
			return false;
		}
		
		// Formats the directory structure for uniformity in methods
		private function formatDir($directory){
			// If directory reference ends with /, remove it to check if directory exists
			if(substr($directory, -1) === '/')
				$directory = substr($dir, 0, -1);
			// Remove any leading slashes
			if(substr($directory, 0, 1) === '/')
				$directory = substr($directory, 1);
			// Just to be certain, check to see if the directory is actually a directory
			if(is_dir($directory)){
				return $directory;
			}else{
				return false;
			}
		}
	}
/******************************************************
				Fix Everything Class
				--------------------
- So we still need PDO / MySQLi / MySQL to return the tables and to parse the prefixes
- Right now we can do the updates over shell, as they do not require any verification
-------
Methods
-------
	get_prefix_list()
	get_table_list()
	rename_tables()	
	fix_usermeta()
	fix_options()
	screen_wp(string)
	get_url()
	set_url()
	table_check()
******************************************************/
	class fix_things {
		public $new_prefix = '';			// The desired prefix for the tables
		public $old_prefix = '';			// The old prefix being changed
		public $good_tables = array();		// Tables associated with the old prefix
		public $prefix_array = array();		// Prefixes in database that do not match the old prefix
		private $pdo = null;				// PDO wpaas_connection object
		public $display;					// Display object
		
		public function __construct(){
			$this->pdo = new wpaas_connection();
			$this->display = new display();
		}
		
		/*
			get_prefix_list()
			--------------
			Sets the prefix_array based on parsing the _usermeta from the database
			--------------
			TODO:
			- Sanitize $new_prefix input with bindparams
			- After getting a prefix list, test by searching for site url in the prefix list to finalize
		*/
			
		public function get_prefix_list(){
			$sql = "SHOW TABLES LIKE '%_usermeta'";

			$result = $this->pdo->run($sql);
			$this->pdo->disconnect();

			// If the count is greater than 1 deep in the array (multiple usermeta), then prefix will be false. Otherwise it will assign the old prefix.
			if((count($result) > 1)||(empty($result)))
				$this->old_prefix = false;
			else
				$this->old_prefix = str_replace('usermeta', '', reset($result));
			
			return $this->old_prefix;
		}
		
		/*
			get_table_list(string)
			--------------
			Get affected tables specific to a prefix
			--------------
		*/
		private function get_table_list(){
			
			// Check to see if the old prefix has been sorted yet
			if(empty($this->old_prefix)){
				$this->get_prefix_list();	// Get prefix list for prefix_array check
			}
						
			// Get the table list so long as multiple usermeta tables are not detected
			$sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = '".DB_NAME."'  AND table_name LIKE '".$this->old_prefix."%'";
			if($this->old_prefix !== false){
				// NOT LIKE condition will exclude all tables if either condition = wp_
				$sql .= $this->screen_wp('table_name');

				// Get the targeted tables that we will use to rename to the proper prefix
				$table_array = $this->pdo->runAll($sql);
				$this->pdo->disconnect();
				
				// Tables return as a 2D array
				foreach($table_array as $database => $nested){
					foreach($nested as $k => $table){
						if(!in_array($table, $this->good_tables)){
							$this->good_tables[] .= $table;
						}
					}
				}
			}
		}
		
		/*
			rename_tables()
			--------------
			Renames the tables on the database
			--------------
		*/
		public function rename_tables(){
			if($this->old_prefix !== false){
				$this->get_table_list();
				$this->new_prefix = PREFIX;
				$sql = 'RENAME TABLE ';
						
				// Construct the SQL statement to rename
				foreach($this->good_tables as $tbl){
					$tbl_name = preg_replace('/'.$this->old_prefix.'/', '', $tbl, 1);
					$b_value = $this->new_prefix.$tbl_name;
	
					$sql .= $tbl.' TO '.$b_value.', ';
				}
	
				// Clean up the SQL statement to remove the , and space from the end.
				$sql = substr($sql, 0, -2);
		
				$this->pdo->run($sql);
				
				// Now begin updating the other values in the database
				$this->fix_usermeta();
				$this->fix_options();
				
				$this->pdo->disconnect();
				
				return true;
			}else{
				return false;
			}
		}
		
		/*
			fix_usermeta()
			--------------
			Fixes usermeta prefixes based on old prefix
			--------------
		*/
		private function fix_usermeta(){
			$table = $this->new_prefix."usermeta";
			
			$sql = "UPDATE ".$table." SET meta_key = REPLACE (meta_key, '".$this->old_prefix."', '".PREFIX."') WHERE meta_key LIKE '".$this->old_prefix."%'";
			
			$sql .= $this->screen_wp('meta_key');
			
			$this->pdo->run($sql);
			$this->pdo->disconnect();
			
			}
			/*
			fix_options()
			--------------
			Fixes specific options prefixes
			--------------
			TODO:
			- Add in return value based on SQL execution
		*/
		private function fix_options(){
			$table = $this->new_prefix."options";
			$sql = "UPDATE ".$table." SET option_name = REPLACE (option_name, '".$this->old_prefix."', '".PREFIX."') WHERE option_name LIKE '".$this->old_prefix."%'";
			
			$sql .= $this->screen_wp('option_name');
			
			$this->pdo->run($sql);
		}
		
		// Simple method that screens the prefixes for wp_ logic matching and constructs SQL based on result
		private function screen_wp($key){
			$sql = '';
			if(($this->old_prefix !== 'wp_')&&(PREFIX !== 'wp_')&&($this->old_prefix !== PREFIX)){
				$sql .= " AND ".$key." NOT LIKE '".PREFIX."%'";
			}
			
			return $sql;
		}
		
		/*
			get_url()
			--------------
			Gets the current active URL on the database
			--------------
			TODO:
			- Chain URL to prefix if multiple prefixes exist
		*/
		public function get_url(){
			
			// Get the prefix list first. If multiple exist for options, then break and tell rep to drop tables.
			if(empty($this->old_prefix))
				$this->get_prefix_list();
			
			// Return false if mutiple wp_usermeta tables exist
			if($this->old_prefix === false){
				return false;
			}else{
				// $this->new_prefix is only assigned when the tables are updated
				$prefix = (empty($this->new_prefix) ? $this->old_prefix : $this->new_prefix);

				// Get the siteurl
				$sql = "SELECT option_value FROM ".$prefix."options WHERE option_name =  'siteurl'";
						
				$result = $this->pdo->run($sql);
				$this->pdo->disconnect();
				
				/* if(!empty($result))
					$response = (is_array($result) ? $result[0] : $result);
				else
					$response = false; */
				if(!empty($result))
					$result = reset($result);
				else
					$result = false;
							
				return $result;
			}
			
		}
		
		/*
			set_url()
			--------------
			Updates the site URL
			--------------
		*/
		public function set_url(){
			// Since the new prefix is only set in rename tables, it will only use new prefix if tables have changed
			if(!empty($this->new_prefix))
				$prefix = $this->new_prefix;
			else
				$prefix = $this->old_prefix;
			// Site URL Update
			$sql = 	"UPDATE ".$prefix."options SET option_value = '".SITE_URL."' WHERE option_name = 'siteurl';
					UPDATE ".$prefix."options SET option_value = '".SITE_URL."' WHERE option_name = 'home'";
			$this->pdo->run($sql);
			$this->pdo->disconnect();
		}
		/*
			table_check()
			--------------
			Verifies that all necessary tables exist for WordPress
			--------------
		*/
		public function table_check(){
			// List of default WordPress tables
			$required_tables = array(
							'commentmeta', 
							'comments', 
							'links', 
							'options', 
							'postmeta', 
							'posts', 
							'terms', 
							'term_relationships', 
							'term_taxonomy', 
							'usermeta', 
							'users'
			);
			
			// Show tables to compare with normal WordPress tables
			$sql = 'SHOW TABLES;';
			$result = $this->pdo->runAll($sql);
			$this->pdo->disconnect();
			
			// Checks the database results and compares it with the required tables
			foreach($result as $key => $database){
				foreach($database as $k=> $dbtable){
					foreach($required_tables as $rk => $table){
						// If it locates the required table, it removes it from the array
						if(strpos($dbtable, $table) !== false)
							unset($required_tables[$rk]);
					}
				}
			}
			
			// If tables are empty and we can get the site url, then return true
			if(empty($required_tables)){
				if($this->get_url() !== false)
					return true;
			}
			
			// Otherwise return false
			return false;	
		}
		
	}
	

/******************************************************
				Begin Procedural Logic
******************************************************/
// Fire the Get Started data
$get_started = new get_started();

// Selector for restoring SQL backups and displaying current prefixes
$s = new selector();

// Check if this is an AJAX response:
if(!isset($_POST['selector'])){
/******************************************************
				Begin HTML Output
******************************************************/
// May eventually change this to use ob_start / ob_get_clean, though is not necessary for this implementation
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>WordPress - FATT (Lite)</title>

		<!-- Bootstrap -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
        <style>
		@font-face{font-family:Walsheim-Light;font-style:normal;font-weight:400;src:url(//img1.wsimg.com/ux/fonts/1.0/eot/w3.eot?#iefix) format("embedded-opentype"),url(//img1.wsimg.com/ux/fonts/1.0/woff/w3.woff) format("woff"),url(//img1.wsimg.com/ux/fonts/1.0/ttf/w3.ttf) format("truetype")}@font-face{font-family:Walsheim-Regular;font-style:normal;font-weight:400;src:url(//img1.wsimg.com/ux/fonts/1.0/eot/w4.eot?#iefix) format("embedded-opentype"),url(//img1.wsimg.com/ux/fonts/1.0/woff/w4.woff) format("woff"),url(//img1.wsimg.com/ux/fonts/1.0/ttf/w4.ttf) format("truetype")}@font-face{font-family:Walsheim-Medium;font-style:normal;font-weight:400;src:url(//img1.wsimg.com/ux/fonts/1.0/eot/w5.eot?#iefix) format("embedded-opentype"),url(//img1.wsimg.com/ux/fonts/1.0/woff/w5.woff) format("woff"),url(//img1.wsimg.com/ux/fonts/1.0/ttf/w5.ttf) format("truetype")}@font-face{font-family:Walsheim-Bold;font-style:normal;font-weight:400;src:url(//img1.wsimg.com/ux/fonts/1.0/eot/w7.eot?#iefix) format("embedded-opentype"),url(//img1.wsimg.com/ux/fonts/1.0/woff/w7.woff) format("woff"),url(//img1.wsimg.com/ux/fonts/1.0/ttf/w7.ttf) format("truetype")}@font-face{font-family:Walsheim-Black;font-style:normal;font-weight:400;src:url(//img1.wsimg.com/ux/fonts/1.0/eot/w8.eot?#iefix) format("embedded-opentype"),url(//img1.wsimg.com/ux/fonts/1.0/woff/w8.woff) format("woff"),url(//img1.wsimg.com/ux/fonts/1.0/ttf/w8.ttf) format("truetype")}
		h1, h2, h3, .h1, .h2, .h3 {font-family:"Walsheim-Black";}
		h4, h5, h6, .h4, .h5, .h6 {font-family:"Walsheim-Medium";}
		p{font-family:Arial,"Helvetica Neue",Helvetica,sans-serif;}
		label, .list-group-item, .list-group-item-text{font-family:"Walsheim-Regular";}
		.alert{border-radius:0;}
		.btn{font-family:"Walsheim-Medium",Arial,sans-serif;}
		body{background-color:#e8e8e8;}
		.update-notice{border-radius:0; margin-bottom:0;}
		.col-no-pad{padding:0;}
		.navbar-fatt{background-color:#1d6ccd;border-radius:0;border:none;}
		.navbar-brand{color:#FFF;}
		.navbar-brand:hover, .navbar-brand:focus{outline:0;color:#FFF;background-color:#3B90F7;}
		#main-content{font-family:"Helvetica Neue","Segoe UI",Segoe,Helvetica,Arial,"Lucida Grande",sans-serif;}
		.btn-default, .btn-default:hover, .btn-default:focus, .btn-default:active, .btn-default.active, .open .dropdown-toggle.btn-default{margin:5px 3px; background-color:#8b8b8b; border-radius:0; color:#FFF;font-weight:bold;border-bottom:4px solid #7e7e7e;}
		.btn-default.disabled, .btn-default[disabled], fieldset[disabled] .btn-default, .btn-default.disabled:hover, .btn-default[disabled]:hover, fieldset[disabled] .btn-default:hover, .btn-default.disabled:focus, .btn-default[disabled]:focus, fieldset[disabled] .btn-default:focus, .btn-default.disabled:active, .btn-default[disabled]:active, fieldset[disabled] .btn-default:active, .btn-default.disabled.active, .btn-default.active[disabled], fieldset[disabled] .btn-default.active{background-color:#CCC;}
		.btn-default:hover, .btn-default:focus, .btn-default:active, .btn-default.active, .open .dropdown-toggle.btn-default{background-color:#939393;}
		.nav > li > a:hover, .nav > li > a:focus, .nav-pills > li.active > a, .nav-pills > li.active > a:hover, .nav-pills > li.active > a:focus{background-color:#1d6ccd; color:#FFF;outline:none;}
		.btn-info {border-color: #000; background-color:#333; border-radius: 0; font-size: 18px; line-height: 20px; margin: 0; padding: 15px 0;  width: 100%;}
		.btn-info:hover, .btn-info:focus, .btn-info:active, .btn-info.active, .open .dropdown-toggle.btn-info{background-color:#13488A; border-color:#000;}
		.box-select{cursor: pointer; margin-top:7px; margin-bottom:7px; padding:10px 5px; text-align:center; background-color:#f5f5f5; border-radius:10px; color:#333; font-weight:bold;}
		.box-select:hover{}
		.box-select.active, button-select.active:hover{background-color:#1d6ccd; color:#FFF; border-color:#e67c00;}
		.alert-heading{background-color:#333; color:#FFF; border-radius:0;}
		.well{background-color:#FFF; border-radius:0; box-shadow:4px 4px 0 0 rgba(0, 0, 0, 0.1); padding:10px;}
		a.jumbotron-links{color:#000;}
		a.jumbotron-links:hover, a.jumbotron-links:hover .alert{background-color:#1d6ccd; text-decoration:none;}
		.jumbotron-links .well{cursor:pointer; padding:0;}
		.jumbotron-links .alert{margin:0;}
		.jumbotron-links .jumbotron{background-color:transparent; margin-bottom:0; padding-left:0; padding-right:0;}
		.glyphicon-size-massive{font-size:5em;}
		.ux-pagetitle {background-color: #fff; box-shadow: 0 4px 0 rgba(0, 0, 0, 0.1); color: #333; margin-bottom: 30px; margin-top: -20px !important; padding: 15px 0;}
		p{word-wrap:break-word;}
		</style>
	</head>
	<body>
		<header>
			<div class="navbar navbar-fatt col-no-pad" role="navigation">
				<div class="col-xs-6">
					<a class="navbar-brand" href="#">WP-FATT <i>Lite</i></a>
				</div>
				<div class="col-xs-6">
					<a class="navbar-brand pull-right" href="#">Version: <?=VERSION;?></a>
				</div>
				<div class="col-sm-12">
					<div class="row">
						<div class="col-md-4 text-center col-no-pad">
							<form role="form" method="post">
								<input type="hidden" name="selector" value="killTransient" />
								<button type="submit" class="btn btn-info">Clear the Cache (SQL/Varnish)</button>
							</form>
						</div>
						<div class="col-md-4 text-center col-no-pad">
							<form role="form" method="post">
								<input type="hidden" name="selector" value="killProcesses" />
								<button type="submit" class="btn btn-info">Kill PHP Processes</button>
							</form>
						</div>
						<div class="col-md-4 text-center col-no-pad">
							<form role="form" method="post">
								<input type="hidden" name="selector" value="killFatt" />
								<button type="submit" class="btn btn-info">Remove FATT</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</header>
        
         <div id="main-content">
			<?php if($get_started->display->msg === true){ ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row ux-pagetitle">
                            <div class="col-md-offset-1 col-md-5">
                                <h3>WordPress FATT</h3>
								<h4><b>FATT Version:</b><font color ="#1d6ccd"> <?=VERSION;?></font></h4>
								<h4><b>FATT Archive:</b><font color ="#1d6ccd"> <?=$_SESSION['tarball'];?></font></h4>
								<h4><b>FATT DB Backup:</b><font color ="#1d6ccd"> <?=$_SESSION['sql_backup'];?></font></h4>
                            </div>
                            <div class="col-md-5 pull-right">
                                <h3><b>DATABASE NAME:</b><font color ="#1d6ccd"> <?=DB_NAME;?></font></h3>
								<h4><b>WordPress Version:</b><font color ="#1d6ccd"> <?=WP_VER;?></font></h4>
								<h4><b>PHP Version:</b><font color ="#1d6ccd"> <?=phpversion();?></h4></font>
								<h4><b>Web Node:</b><font color ="#1d6ccd"> <?=W_NODE;?></h4></font>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="row">
                	<div class="col-sm-offset-2 col-sm-8">
                    	<div class="row">
                            <div id="fatt-menu" class="carousel slide" data-ride="carousel">
                                <!-- Wrapper for carousel panes -->
                                <div class="carousel-inner" role="listbox">
                                    <!-- Main Menu -->
                                    <div class="item active">
                                        <div class="col-xs-12">
                                            
                                            <!-- Menu items -->
                                            <div class="row">
                                                <a class="jumbotron-links" data-target="#fatt-menu" data-slide-to="1">
                                                    <div class="col-sm-3">
                                                        <div class="well">
                                                            <h4 class="alert alert-heading">BACKUP / RESTORE THE THINGS!</h4>
                                                            <div class="row">
                                                                <div class="col-lg-12 text-center jumbotron">
                                                                    <span class="glyphicon glyphicon-transfer glyphicon-size-massive"></span>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="alert alert-heading small">
                                                                        <p class="list-group-item-text hidden-xs">
                                                                            Backup Current Database
                                                                        </p>
                                                                        <p class="list-group-item-text hidden-xs">
                                                                            Restore Database From Backup
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
												
												<a class="jumbotron-links" data-target="#fatt-menu" data-slide-to="2">
                                                    <div class="col-sm-3">
                                                        <div class="well">
                                                            <h4 class="alert alert-heading">PACK THE THINGS!</h4>
                                                            <div class="row">
                                                                <div class="col-lg-12 text-center jumbotron">
                                                                    <span class="glyphicon glyphicon-compressed glyphicon-size-massive"></span>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="alert alert-heading small">
                                                                        <p class="list-group-item-text hidden-xs">
                                                                           Tarball EVERYTHING! <br>  (DB and Files)
                                                                        </p>
                                                                        <p class="list-group-item-text hidden-xs">
                                                                            Unpack a plethora of archive types!
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                
                                                <a class="jumbotron-links" data-target="#fatt-menu" data-slide-to="3">
                                                    <div class="col-sm-3">
                                                        <div class="well">
                                                            <h4 class="alert alert-heading">UPDATE THE THINGS!</h4>
                                                            <div class="row">
                                                                <div class="col-lg-12 text-center jumbotron">
                                                                    <span class="glyphicon glyphicon-wrench glyphicon-size-massive"></span>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="alert alert-heading small">
                                                                        <p class="list-group-item-text hidden-xs">
                                                                            Synchronize Table Prefixes
							
																		</p>
                                                                        <p class="list-group-item-text hidden-xs">
                                                                            Update Site / Home URL
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                               
                                                <a class="jumbotron-links" data-target="#fatt-menu" data-slide-to="4">
                                                    <div class="col-sm-3">
                                                        <div class="well">
                                                            <h4 class="alert alert-heading">INI THE THINGS!</h4>
                                                            <div class="row">
                                                                <div class="col-lg-12 text-center jumbotron">
                                                                    <span class="glyphicon glyphicon-cog glyphicon-size-massive"></span>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="alert alert-heading small">
                                                                        <p class="list-group-item-text hidden-xs">
                                                                            Create a PHP initialization file
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Backup / Import MySQL Databases -->
                                    <div class="item">
                                        <div class="col-sm-12">
                                            <h2 class="alert alert-heading">
                                                 <a class="btn btn-warning" data-target="#fatt-menu" data-slide-to="0"><span class="glyphicon glyphicon-chevron-left"></span></a>                                                 BACKUP/RESTORE THE THINGS!
                                            </h2>
                                            	
                                            <div class="row">
                                                <div class="col-md-4">
                                                	<div class="well">
                                                    	<?=$get_started->db_output;?>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-8">
                                                	<div class="well">
                                                    	<div class="row">
                                                        	<div class="col-sm-12">
                                                            	<h3>Restore Backup</h3>
                                                        		<?=$s->select_sql_backup();?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
												
                                            </div>
                                        </div>
                                    </div>
									
									<!-- Tarball Archive -->
                                    <div class="item">
                                        <div class="col-sm-12">
                                        	<h2 class="alert alert-heading">
                                                 <a class="btn btn-warning" data-target="#fatt-menu" data-slide-to="0"><span class="glyphicon glyphicon-chevron-left"></span></a>
                                                 PACK THE THINGS!
                                            </h2>
                                            <div class="row">
                                                <div class="col-md-5">
                                                	<div class="well">
                                                        <h3>Create Tarball Archive</h3>
                                                        <p>This goes through the following steps:</p>
                                                        <ol>
                                                            <li>Creates a .sql.gz dump within wp-content/</li>
                                                            <li>Archives the MySQL backup AND wp-content/ as a .tar.gz file within wp-content/ for easy transferring</li>
                                                        </ol>
														<div class="row">
															<div class="col-sm-12">
																<form id="create_archive" name="create_archive" role="form" method="post">
																	<input type="hidden" name="selector" value="create_archive">
																	<button type="submit" class="btn btn-default">CREATE ARCHIVE</button>
																</form>
															</div>
														</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                	<div class="well">
                                                        <h3>Unpack Archive</h3>
                                                        <div class="row">
                                                        	<div class="col-sm-12 text-info">
                                                            	<p>This will unarchive the following extensions:</p>
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <ul class="list-unstyled">
                                                                            <li>.zip</li>
                                                                            <li>.tar</li>
                                                                            <li>.gz</li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <ul class="list-unstyled">
                                                                            <li>.tar.gz</li>
                                                                            <li>.bz2</li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                           
                                                        </div>
                                                        
                                                        <div class="row">
                                                        	<div class="col-sm-12">
                                                        		<?=$s->select_archive();?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Update Prefixes / Site URL -->
                                    <div class="item">
                                         <div class="col-sm-12">
                                            <h2 class="alert alert-heading">
                                                 <a class="btn btn-warning" data-target="#fatt-menu" data-slide-to="0"><span class="glyphicon glyphicon-chevron-left"></span></a>
												 UPDATE THE THINGS!
                                            </h2>
											 <form name="fix_things" class="form-horizontal" role="form" method="post">
												<div class="row">
													<div class="col-sm-6">
														<div class="well">
															<h3>Update Table Prefixes</h3>
															<p>
																The left box will automatically update the table prefixes AND all of the database entries for capabilities, user_roles, etc. that are necessary for WordPress to operate normally.
															</p>
															<div class="row">
																<div class="col-xs-offset-2 col-xs-8 box-select">
																	<?=$s->get_prefixes();?>
																</div>
															</div>
														</div>
													</div>
													<div class="col-sm-6">
														<div class="well">
															<h3>Update Site URL</h3>
															<p>The right box will update the siteurl and home database values to reflect the domain being currently used. You may select either, or both, of these options then simply press Update</p>
															<div class="row">
																 <div class="col-xs-offset-2 col-xs-8 box-select">
																	<?=$s->get_url();?>
																</div>
															</div>
														</div>
													</div>
													
													<input type="hidden" name="selector" value="fix_things">
												</div>
												<div class="row">
													<div class="col-sm-12 text-center">
														<button type="submit" class="btn btn-default">
															UPDATE<br />
															<span class="small">This will remove the script</span>
														</button>
													</div>
												</div>
											</form>
                                        </div>
                                    </div>
									
                                    <!-- Simple INI Generator -->
                                    <div class="item">
                                         <div class="col-sm-12">
											<h2 class="alert alert-heading">
                                                 <a class="btn btn-warning" data-target="#fatt-menu" data-slide-to="0"><span class="glyphicon glyphicon-chevron-left"></span></a>
                                                 INI THE THINGS!
                                            </h2>
                                        </div>
                                        <form name="create-ini" class="form-horizontal" role="form" method="post">
                                            <div class="col-sm-12">
												<div class="well">
													<div class="row">
														<div class="col-md-4 hidden-xs">
															<p>
																This function will create a PHP initialization file using the PHP directives from the form below. When 'CREATE INI' is clicked it will create the file, kill PHP processes and self-destruct. You may need to reupload the file if this is not the only function you are using FATT for.
															</p>
															<table class="table table-hover">
																<thead>
																	<tr>
																		<th>Environment</th>
																		<th>INI file</th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td>Shared cPanel
																			<a href="#" data-toggle="tooltip" title="cPanel also supports php.ini">
																				<span class="small glyphicon glyphicon-info-sign"></span>
																			</a>
																		</td>
																		<td>.user.ini</td>
																	</tr>
																	<tr>
																		<td>Shared Plesk</td>
																		<td>.user.ini</td>
																	</tr>
																	<tr>
																		<td>
																			Managed WordPress
																			<a href="#" data-toggle="tooltip" title="WPaaS also supports php.ini, but .user.ini is recommended">
																				<span class="small glyphicon glyphicon-info-sign"></span>
																			</a>
																		</td>
																		<td>.user.ini</td>
																	</tr>
																	<tr>
																		<td>Other Legacy Shared</td>
																		<td>php5.ini</td>
																	</tr>
																</tbody>
															</table>
															<p class="text-info small">
																.user.ini files do not let customers modify ALL of the same settings as a php.ini file. The .user.ini file is only valid for PHP 5.3+ installations.
															</p>
															<p class="text-info small">
																See article 8913 for more information.
															</p>
														</div>
														
														<div class="col-md-8">
															<div class="row">
																<div class="col-md-12">
																	<div class="form-group">
																		<label for="ini_select" class="col-md-6 control-label">INI File</label>
																		<div class="col-md-3">
																			<select class="form-control" name="ini_select">
																				<option value=".user.ini">.user.ini</option>
																				<option value="php.ini">php.ini</option>
																				<option value="php5.ini">php5.ini</option>
																			</select>
																		</div>
																	</div>
																	<div class="form-group">
																		<label for="php_memory_limit" class="col-md-6 control-label">
																			Memory Limit
																			<a href="#" data-toggle="tooltip" title="The amount of memory allocated IN MEGABYTES to a single PHP processes.">
																				<span class="small glyphicon glyphicon-question-sign"></span>
																			</a>
																		</label>
																		
																		<?php // -- memory_limit default --
																			$ini_value = ini_get('memory_limit'); 
																		?>
																		<div class="col-md-3">
																			<select class="form-control" name="php_memory_limit" selected>
																				<option value="<?=$ini_value;?>">
																					* <?=$ini_value;?> *
																				</option>
																				<option value="128M">128M</option>
																				<option value="256M">256M</option>
																				<option value="512M">512M</option>
																				<option value="1024M">1024M</option>
																			</select>
																		</div>
																	</div>
																	
																	<div class="form-group">
																		<label for="php_max_execution_time" class="col-md-6 control-label">
																			Max Execution Time
																			<a href="#" data-toggle="tooltip" title="The time allowed IN SECONDS for the entire script to execute before timing out.">
																				<span class="small glyphicon glyphicon-question-sign"></span>
																			</a>
																		</label>
																		
																		<?php // -- max_execution default -- 
																			$ini_value = ini_get('max_execution_time'); 
																		?>
																		<div class="col-md-3">
																			<select class="form-control" name="php_max_execution_time">
																				<option value="<?=$ini_value;?>" selected>
																					* <?=$ini_value;?> *
																				</option>
																				<option value="30">30</option>
																				<option value="60">60</option>
																				<option value="90">90</option>
																				<option value="120">120</option>
																				<option value="300">300</option>
																			</select>
																		</div>
																	</div>
																	
																	<div class="form-group">
																		<label for="php_max_input_time" class="col-md-6 control-label">
																			Max Input Time
																			<a href="#" data-toggle="tooltip" title="The time allowed IN SECONDS for PHP to parse input variables (like GET and POST) before timing out.">
																				<span class="small glyphicon glyphicon-question-sign"></span>
																			</a>
																		</label>
																		
																		<?php // -- max_input_time default -- 
																			$ini_value = ini_get('max_input_time'); 
																		?>
																		<div class="col-md-3">
																			<select class="form-control" name="php_max_input_time">
																				<option value="<?=$ini_value;?>" selected>
																					* <?=$ini_value;?> *
																				</option>
																				<option value="60">60</option>
																				<option value="90">90</option>
																				<option value="120">120</option>
																				<option value="120">150</option>
																				<option value="300">300</option>
																			</select>
																		</div>
																	</div>
																	
																	<div class="form-group">
																		<label for="php_post_max_size" class="col-md-6 control-label">
																			POST Max Size
																			<a href="#" data-toggle="tooltip" title="The largest size IN MEGABYTES a POST request can be (often related to uploads since uploads typically utilize a POST request)">
																				<span class="small glyphicon glyphicon-question-sign"></span>
																			</a>
																		</label>
																		
																		<?php // -- post_max_size default -- 
																			$ini_value = ini_get('post_max_size'); 
																		?>
																		<div class="col-md-3">
																			<select class="form-control" name="php_post_max_size">
																				<option value="<?=$ini_value;?>" selected>
																					* <?=$ini_value;?> *
																				</option>
																				<option value="65M">65M</option>
																				<option value="75M">75M</option>
																				<option value="85M">85M</option>
																				<option value="100M">100M</option>
																			</select>
																		</div>
																	</div>
																	
																	<div class="form-group">
																		<label for="php_max_input_vars" class="col-md-6 control-label">
																			Max Input Variables
																			<a href="#" data-toggle="tooltip" title="The total number of input variables (GET or POST) you can assign in a PHP script.">
																				<span class="small glyphicon glyphicon-question-sign"></span>
																			</a>
																		</label>
																		
																		<?php // -- max_input_vars default -- 
																			$ini_value = ini_get('max_input_vars'); 
																		?>
																		<div class="col-md-3">
																			<select class="form-control" name="php_max_input_vars">
																				<option value="<?=$ini_value;?>" selected>
																					* <?=$ini_value;?> *
																				</option>
																				<option value="1000">1000</option>
																				<option value="2500">2500</option>
																				<option value="4000">4000</option>
																				<option value="5000">5000</option>
																				<option value="10000">10000</option>
																			</select>
																		</div>
																	</div>
																	
																	<div class="form-group">
																		<label for="php_file_uploads" class="col-md-6 control-label">
																			File Uploads
																			<a href="#" data-toggle="tooltip" title="Disable (OFF) or Enable (ON) file uploads in PHP.">
																				<span class="small glyphicon glyphicon-question-sign"></span>
																			</a>
																		</label>
																		
																		<?php // -- file_uploads default -- 
																			$ini_value = ini_get('file_uploads'); 
																		?>
																		<div class="col-md-3">
																			<select class="form-control" name="php_file_uploads">
																				<option value="<?=$ini_value;?>" selected>
																					* <?= ($ini_value ? '1 - ON' : '0 - OFF');?> *
																				</option>
																				<option value="1">1 - ON</option>
																				<option value="0">0 - OFF</option>
																			</select>
																		</div>
																	</div>
																	
																	<div class="form-group">
																		<label for="php_max_file_uploads" class="col-md-6 control-label">
																			Max File Uploads
																			<a href="#" data-toggle="tooltip" title="Maximum number of CONCURRENT uploads that can be performed in PHP.">
																				<span class="small glyphicon glyphicon-question-sign"></span>
																			</a>
																		</label>
																		
																		<?php // -- file_uploads default -- 
																			$ini_value = ini_get('max_file_uploads'); 
																		?>
																		<div class="col-md-3">
																			<select class="form-control" name="php_max_file_uploads">
																				<option value="<?=$ini_value;?>" selected>
																					* <?=$ini_value;?> *
																				</option>
																				<option value="20">20</option>
																				<option value="25">25</option>
																				<option value="30">30</option>
																				<option value="35">35</option>
																			</select>
																		</div>
																	</div>
																	
																	<div class="form-group">
																<label for="php_upload_max_filesize" class="col-md-6 control-label">
																	Max Upload Filesize
																	<a href="#" data-toggle="tooltip" title="Maximum size IN MEGABYTES PHP will allow per uploaded file.">
																		<span class="small glyphicon glyphicon-question-sign"></span>
																	</a>
																</label>
																
																<?php // -- upload_max_filesize default -- 
																	$ini_value = ini_get('upload_max_filesize'); 
																?>
																<div class="col-md-3">
																	<select class="form-control" name="php_upload_max_filesize">
																		<option value="<?=$ini_value;?>" selected>
																			* <?=$ini_value;?> *
																		</option>
																		<option value="64M">64M</option>
																		<option value="100M">100M</option>
																		<option value="150M">150M</option>
																		<option value="200M">200M</option>
																	</select>
																</div>
															</div>
																</div>
															</div>
															
															<div class="row">
																<div class="col-md-12 text-center">
																	<p class="text-small text-muted">
																		<strong>* value *</strong> denotes the current active setting for that variable
																	</p>
																</div>
															</div>
															
														</div><!-- End Select options column -->
													</div>
												</div>
											</div>
            
                                            <div class="col-sm-12 text-center">
                                                <button type="submit" class="btn btn-default">
                                                    CREATE INI<br />
                                                    <span class="small">This will remove FATT</span>
                                                </button><br>
                                            </div>
                                            <input type="hidden" name="selector" value="createINI">
                                        </form>   
                                    </div>
									
								</div>
							</div>
						</div>
						<!-- End Panes -->
					</div>
				</div><!-- End column offset wrapper -->
			</div>                
		</div>        
		<?php 
			}else{ // Something went wrong with FATT and we could not initialize it
				echo '<div class="row text-center">'.$get_started->display->msg.'</div>';
			}
		?>
		
		<!-- JS CDNs for Bootstrap and jQuery -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<script>
			$(function(){
  				$('[data-toggle="tooltip"]').tooltip()	// Enable tooltips
				$('#fatt-menu').carousel({interval:0});	// Set the cycle time to zero for the menu
				// Sets true / false to hidden values for box-select (on/off) toggles
				$('.box-select').on('click', function(){
					var input = $(this).find('input:hidden');
					console.log(input.val());
					$(this).toggleClass('active');
					if($(this).hasClass('active'))
						input.val('true');
					else
						input.val('false');
				});
				<!-- Universal AJAX submission for forms -->
				$("form").submit(function(e){
					var form = $(this),
						sButton = form.find(':submit');
					
					if(!sButton.hasClass('btn-info')){
						var fContainer = sButton.closest('div[class^="col-"]')
					}else{
						var fContainer = sButton.closest('div[class="row"]');						
					}
					serializedData = form.serialize();
					if(fContainer.find('.response').length == 0){	
						fContainer.prepend('<div class="row response"><div class="col-sm-12"></div></div>');
					}
					// Disable all buttons
					$(':button').each(function(){
						$(this).addClass('disabled');
					});
					var output = fContainer.find('.response').find('.col-sm-12');
					output.html('<div class="alert alert-info" role="alert">Doing stuffs and things. Please wait patiently while I do the needful.</div>');
					$.ajax({
						type: $(form).attr('method'),
						url: $(form).attr('action'),
						data: serializedData,
						success: function(response){
							output.html(response);
							// Re-enable all buttons
							$(':button').each(function(){
								$(this).removeClass('disabled');
							});
							setInterval("location.reload()", 1000);
						},
						error: function(x, t, m) {
							if(t==="timeout") {
								output.html('<div class="alert alert-warning" role="alert"><b>Request Timed Out!</b></div>');
							} else if(x.status === 500){
								output.html('<div class="alert alert-warning" role="alert"><p><b>Internal Server Error:</b> It\'s likely the script timed out.</p><p>If you are executing a BASH command (like restoring a database), give it a minute or so to complete. Then check your work.</div>')
							} else if(x.status > 200){
								output.html('<div class="alert alert-warning" role="alert"><p>Something went wrong that we did not account for. May want to send a bug report to me. The HTTP Status code is: ' + x.status + '.</p><p>Please include this in your report.</p></div>');
							}
						}
					});
					e.preventDefault();
				});
				
				/* FATT autoimport toggle */
				$('select[name="archive_file"]').on('change', function(){
					var importCheck = '<div id="ai-mysql" class="checkbox"><label><input type="checkbox" name="import">Auto-import MySQL Database</label></div>'; 
					
					// Display auto-import option
					if($(this).val().indexOf('FATT-archive') !== -1){
						$(this).closest('.form-group').after(importCheck);
					// Remove the auto-import option
					}else{
						if($('#ai-mysql').length > 0){
							$('#ai-mysql').remove();
						}
					}
				});
			});
 
		</script>
	</body>
</html>
<?
}else{
	// The request is an AJAX response, as all forms implement a $_POST['selector']
	$select = new selector($_POST['selector']);
}