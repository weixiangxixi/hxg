<?php defined('G_IN_SYSTEM') or exit('No permission resources.');//G_PLUGIN_APPclass plugin extends SystemAction {			public function __construct(){				}		public function get(){			$plugin = $this->segment(4);			$plugin = str_ireplace('.','',$plugin);			include G_PLUGIN_APP.$plugin.DIRECTORY_SEPARATOR.$plugin.'.plugin.php';			}				public function admin(){			$plugin = $this->segment(4);			$plugin = str_ireplace('.','',$plugin);			include G_PLUGIN_APP.$plugin.DIRECTORY_SEPARATOR.$plugin.'.admin.php';			}				//API		private function Load_Class(){					}		private function Load_Tpl(){					}		private function Load_Fun(){					}}?>