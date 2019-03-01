<?php 

defined('G_IN_SYSTEM')or exit('no');

System::load_app_class('admin',G_ADMIN_DIR,'no');

System::load_app_fun('global',G_ADMIN_DIR);

System::load_sys_fun('user');

class content extends admin {

	private $db;

	public function __construct(){		

		parent::__construct();		

		$this->db=System::load_sys_class('model');

		$this->ment=array();

		$this->categorys=$this->db->GetList("SELECT * FROM `@#_category` WHERE 1 order by `parentid` ASC,`cateid` ASC",array('key'=>'cateid'));

		$this->models=$this->db->GetList("SELECT * FROM `@#_model` WHERE 1",array('key'=>'modelid'));

	}

	//添加新文章
	/* 强制揭晓	*/
	public function goods_one_okt1(){
	
		$gid = 10126;
		$ginfo = $this->db->GetOne("select * from `@#_shoplist` where `id` = '$gid' limit 1");
		if(!$ginfo)_message("没有找到这个商品");
		$jinri_time = time();		
		if($ginfo['xsjx_time']!='0')_message("限时揭晓商品不能手动揭晓");
		/*if($ginfo['shenyurenshu']!='0')_message("该商品还有剩余人数,不能手动揭晓！");*/
			
			System::load_app_fun("pay","pay");
			$this->db->Autocommit_start();
			$ok = pay_insert_shop($ginfo);
			
			if(!$ok){
				$this->db->Autocommit_rollback();
				_message("揭晓失败!");	
			}else{
				$this->db->Autocommit_commit();		
				_message("揭晓成功!");
			}
		
	}
  
	public function article_add(){	

		if(isset($_POST['dosubmit'])){

						

			$cateid = intval($_POST['cateid']);		

			$title =  htmlspecialchars($_POST['title']);

			$title_color = htmlspecialchars($_POST['title_style_color']);

			$title_bold = htmlspecialchars($_POST['title_style_bold']);

			

			$title_style='';

			if($title_color){

				$title_style.='color:'.$title_color.';';

			}

			if($title_bold){

				$title_style.='font-weight:'.$title_bold.';';

			}

			

			$keywords = htmlspecialchars($_POST['keywords']);

			$description = htmlspecialchars($_POST['description']);

			$content = editor_safe_replace(stripslashes($_POST['content']));

			

			$author = isset($_POST['zuoze']) ? htmlspecialchars($_POST['zuoze']) : '';	

			$thumb = isset($_POST['thumb']) ? htmlspecialchars($_POST['thumb']) : '';						

			$picarr = isset($_POST['uppicarr']) ? serialize($_POST['uppicarr']) : serialize(array());			

			

			$posttime = strtotime($_POST['posttime']) ? strtotime($_POST['posttime']) : time();

			$hit = intval($_POST['hit']);

			

			$position_arr = isset($_POST['position']) ? $_POST['position'] : false;

						

			if(empty($title)){_message("标题不能为空");}

			if(!$cateid){_message("栏目不能为空");}				

			$sql="INSERT INTO `@#_article` (`cateid`, `author`, `title`,`title_style`, `thumb`, `picarr`, `keywords`, `description`, `content`, `hit`, `order`, `posttime`) VALUES ('$cateid', '$author', '$title','$title_style', '$thumb', '$picarr', '$keywords', '$description', '$content', '$hit', '1', '$posttime')";

			if($this->db->Query($sql)){

				if($position_arr){

					$posinfo=array();

					$posinfo['id'] = $this->db->insert_id();

					$posinfo['title'] = $title;	

					$posinfo['title_style'] = $title_style;						

					$posinfo['thumb'] = $thumb;								

					$position = System::load_app_model("position",'api');

					$position->pos_insert($position_arr,$posinfo);

				}

					_message("文章添加成功");

				

			}else{

					_message("文章添加失败");

			}

		

			header("Cache-control: private");			

		}

			

		$cateid=intval($this->segment(4));		

		$categorys=$this->categorys;

		$tree=System::load_sys_class('tree');

		$tree->icon = array('│ ','├─ ','└─ ');

		$tree->nbsp = '&nbsp;';

		$categoryshtml="<option value='\$cateid'>\$spacer\$name</option>";

		$tree->init($categorys);	

		$categoryshtml=$tree->get_tree(0,$categoryshtml);

		$categoryshtml='<option value="0">≡ 请选择栏目 ≡</option>'.$categoryshtml;

		if($cateid){			

			$cateinfo=$this->db->GetOne("SELECT * FROM `@#_category` WHERE `cateid` = '$cateid' LIMIT 1");

			if(!$cateinfo)_message("参数不正确,没有这个栏目",G_ADMIN_PATH.'/'.ROUTE_C.'/addarticle');

			$categoryshtml.='<option value="'.$cateinfo['cateid'].'" selected="true">'.$cateinfo['name'].'</option>';			

		}		

		include $this->tpl(ROUTE_M,'article.insert');

	}

	

	//文章编辑

	public function article_edit(){	

		$id=intval($this->segment(4));		

		$info=$this->db->GetOne("SELECT * FROM `@#_article` where `id`='$id' LIMIT 1");	

		

		if(isset($_POST['dosubmit'])){

			$cateid = intval($_POST['cateid']);		

			$title =  htmlspecialchars($_POST['title']);

			$title_color = htmlspecialchars($_POST['title_style_color']);

			$title_bold = htmlspecialchars($_POST['title_style_bold']);

			

			$title_style='';

			if($title_color){

				$title_style.='color:'.$title_color.';';

			}

			if($title_bold){

				$title_style.='font-weight:'.$title_bold.';';

			}

			

			$keywords = htmlspecialchars($_POST['keywords']);

			$description = htmlspecialchars($_POST['description']);

			$content = editor_safe_replace(stripslashes($_POST['content']));

			

			$author = isset($_POST['zuoze']) ? htmlspecialchars($_POST['zuoze']) : '';	

			$thumb = isset($_POST['thumb']) ? htmlspecialchars($_POST['thumb']) : '';						

			$picarr = isset($_POST['uppicarr']) ? serialize($_POST['uppicarr']) : serialize(array());			

			

			$posttime = strtotime($_POST['posttime']) ? strtotime($_POST['posttime']) : time();

			$hit = intval($_POST['hit']);

			

			$position_arr = isset($_POST['position']) ? $_POST['position'] : false;

						

			if(empty($title)){_message("标题不能为空");}

			if(!$cateid){_message("栏目不能为空");}		

			

			$sql="UPDATE `@#_article` SET `cateid`='$cateid', 

										  `author`='$author', 

										  `title`='$title',

										  `title_style` = '$title_style', 

										  `keywords`='$keywords', 

										  `description`='$description',

										  `thumb`='$thumb',

										  `picarr`='$picarr',										  

										  `content`='$content', 

										  `posttime`='$posttime',

										  `hit` = '$hit'

										  WHERE (`id`='$id')";

			$this->db->Query($sql);

			if($position_arr){

					$posinfo=array();

					$posinfo['id'] = $info['id'];

					$posinfo['title'] = $title;	

					$posinfo['title_style'] = $title_style;						

					$posinfo['thumb'] = $thumb;								

					$position = System::load_app_model("position",'api');

					$position->pos_insert($position_arr,$posinfo);

			}

				

			if($this->db->affected_rows()){

				_message("操作成功!");

			}else{

				_message("操作失败!");

			}

			header("Cache-control: private");

		}



		

			

		if(!$info)_message("参数错误");

		$cateinfo=$this->db->GetOne("SELECT * FROM `@#_category` WHERE `cateid` = '$info[cateid]' LIMIT 1");		

		

		$categorys=$this->categorys;

		$tree=System::load_sys_class('tree');

		$tree->icon = array('│ ','├─ ','└─ ');

		$tree->nbsp = '&nbsp;';

		$categoryshtml="<option value='\$cateid'>\$spacer\$name</option>";

		$tree->init($categorys);	

		$categoryshtml=$tree->get_tree(0,$categoryshtml);		

		$categoryshtml.='<option value="'.$cateinfo['cateid'].'" selected="true">'.$cateinfo['name'].'</option>';

		

		$this->ment=array(

						array("lists","内容管理",ROUTE_M.'/'.ROUTE_C."/article_list"),

						array("insert","添加文章",ROUTE_M.'/'.ROUTE_C."/article_add"),

		);

		

		$info['picarr']=unserialize($info['picarr']);		

		$info['posttime'] = date("Y-m-d H:i:s",$info['posttime']);

		if($info['title_style']){		

			if(stripos($info['title_style'],"font-weight:")!==false){

				$title_bold = 'bold';

			}else{

				$title_bold = '';

			}

			if(stripos($info['title_style'],"color:")!==false){

				$title_color = explode(';',$info['title_style']); 

				$title_color = explode(':',$title_color[0]); 

				$title_color = $title_color[1];

			}else{

				$title_color = '';

			}				

		}else{

			$title_color='';

			$title_bold = '';

		}		

		

		include $this->tpl(ROUTE_M,'article.edit');

	

	}	

	//文章列表

	public function article_list(){

		

		$this->ment=array(

						array("lists","文章管理",ROUTE_M.'/'.ROUTE_C."/article_list"),

						array("insert","添加文章",ROUTE_M.'/'.ROUTE_C."/article_add"),

		);		

		

		$cateid=intval($this->segment(4));

		$list_where = '';

		if(!$cateid){

			$list_where = "1";

		}else{

			$list_where = "`cateid` = '$cateid'";

		}

		if(isset($_POST['sososubmit'])){			

			$posttime1 = !empty($_POST['posttime1']) ? strtotime($_POST['posttime1']) : NULL;

			$posttime2 = !empty($_POST['posttime2']) ? strtotime($_POST['posttime2']) : NULL;			

			$sotype = $_POST['sotype'];

			$sosotext = $_POST['sosotext'];			

			if($posttime1 && $posttime2){

				if($posttime2 < $posttime1)_message("结束时间不能小于开始时间");

				$list_where = "`posttime` > '$posttime1' AND `posttime` < '$posttime2'";

			}

			if($posttime1 && empty($posttime2)){				

				$list_where = "`posttime` > '$posttime1'";

			}

			if($posttime2 && empty($posttime1)){				

				$list_where = "`posttime` < '$posttime2'";

			}

			if(empty($posttime1) && empty($posttime2)){				

				$list_where = false;

			}			

			

			if(!empty($sosotext)){			

				if($sotype == 'cateid'){

					$sosotext = intval($sosotext);

					if($list_where)

						$list_where .= "AND `cateid` = '$sosotext'";

					else

						$list_where = "`cateid` = '$sosotext'";

				}

				if($sotype == 'catename'){

					$sosotext = htmlspecialchars($sosotext);

					$info = $this->db->GetOne("SELECT * FROM `@#_category` where `name` = '$sosotext' LIMIT 1");

					

					if($list_where && $info)

						$list_where .= "AND `cateid` = '$info[cateid]'";

					elseif ($info)

						$list_where = "`cateid` = '$info[cateid]'";

					else

						$list_where = "1";

				}

				if($sotype == 'title'){

					$sosotext = htmlspecialchars($sosotext);

					$list_where = "`title` = '$sosotext'";

				}

				if($sotype == 'id'){

					$sosotext = intval($sosotext);

					$list_where = "`id` = '$sosotext'";

				}

			}else{

				if(!$list_where) $list_where='1';					

			}		

		}	

		$num=20;

		$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_article` WHERE $list_where");

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		$articlelist=$this->db->GetPage("SELECT * FROM `@#_article` WHERE $list_where order by `order` DESC",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));		

		include $this->tpl(ROUTE_M,'article.lists');

	}

	

	//模型

	public function model(){

		$models=$this->models;	

		include $this->tpl(ROUTE_M,'content.model');

	}

	

	public function lists(){

		$categorys=$this->categorys;

		$models=$this->models;

		$tree=System::load_sys_class('tree');

		$tree->icon = array('│ ','├─ ','└─ ');

		$tree->nbsp = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

		foreach($categorys as $v){

			$v['typename']=cattype($v['model']);		

			if($v['model']==-1 || $v['model']==-2){

				$v['seecontent']=G_ADMIN_PATH.'/category/editcate/';

				$v['addcontent']=G_ADMIN_PATH.'/category/editcate/';

			}			

			if($v['model']>0){

				$v['seecontent']=G_ADMIN_PATH.'/'.ROUTE_C.'/get/';	

				$v['addcontent']=G_ADMIN_PATH.'/'.ROUTE_C.'/add/';

				$v['model']=$models[$v['model']]['name'];

			}else{

				$v['model']='';

			}

			$categorys[$v['cateid']]=$v;

		}

		$html=<<<HTML

			<tr>

			<td align='center'>\$cateid</td>

            <td align='left'>\$spacer\$name</th>

            <td align='center'>\$typename</td>

            <td align='center'>\$model</td>

            <td align='center'></td>

			<td align='center'>               

				<a href='\$addcontent\$cateid\'>添加内容</a><span class='span_fenge lr5'>|</span>

				 <a href='\$seecontent\$cateid'>查看内容</a><span class='span_fenge lr5'></span>   

            </td>

          </tr>

HTML;



		$tree->init($categorys);

		$html=$tree->get_tree(0,$html);	

		include $this->tpl(ROUTE_M,'content.list');

	}

	function goods_list1(){
		header("Location:/admin/content/goods_list");
	}

	//商品列表	

	public function goods_list(){

		$this->ment=array(

						array("lists","商品管理",ROUTE_M.'/'.ROUTE_C."/goods_list"),

						array("add","添加商品",ROUTE_M.'/'.ROUTE_C."/goods_add"),

						array("renqi","人气商品",ROUTE_M.'/'.ROUTE_C."/goods_list/renqi"),

						array("xsjx","限时揭晓商品",ROUTE_M.'/'.ROUTE_C."/goods_list/xianshi"),

						array("qishu","期数倒序",ROUTE_M.'/'.ROUTE_C."/goods_list/qishu"),

						array("danjia","单价倒序",ROUTE_M.'/'.ROUTE_C."/goods_list/danjia"),

						array("money","商品价格倒序",ROUTE_M.'/'.ROUTE_C."/goods_list/money"),

						array("money","已揭晓",ROUTE_M.'/'.ROUTE_C."/goods_list/jiexiaook"),

						array("money","<font color='#f00'>期数已满商品</font>",ROUTE_M.'/'.ROUTE_C."/goods_list/maxqishu"),

		);		

	    $cateid=$this->segment(4);

	    $arr = $this->db->GetList("SELECT * FROM `@#_category` WHERE `model`='1'");

		$list_where = '';

		if($cateid){

			if($cateid=='jiexiaook'){

				$list_where = "`q_uid` is not null";

			}

			if($cateid=='maxqishu'){

				$list_where = "`qishu` = `maxqishu` and `q_end_time` is not null and `q_uid` is not null";

			}			

			if($cateid=='renqi'){

				$list_where = "`renqi` = '1' and `q_uid` is null";

			}

			if($cateid=='xianshi'){

				$list_where = "`xsjx_time` != '0'";

			}

			if($cateid=='qishu'){

				$list_where = "1 and `q_uid` is null order by `qishu` DESC";

				$this->ment[4][1]="期数正序";

				$this->ment[4][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/qishuasc";

			}

			if($cateid=='qishuasc'){

				$list_where = "1 and `q_uid` is null order by `qishu` ASC";

				$this->ment[4][1]="期数倒序";

				$this->ment[4][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/qishu";

			}

			if($cateid=='danjia'){

				$list_where = "1  and `q_uid` is null order by `yunjiage` DESC";

				$this->ment[5][1]="单价正序";

				$this->ment[5][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/danjiaasc";

			}

			if($cateid=='danjiaasc'){

				$list_where = "1 and `q_uid` is null order by `yunjiage` ASC";

				$this->ment[5][1]="单价倒序";

				$this->ment[5][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/danjia";

			}

			if($cateid=='money'){

				$list_where = "1 and `q_uid` is null order by `money` DESC";

				$this->ment[6][1]="商品价格正序";

				$this->ment[6][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/moneyasc";

			}

			if($cateid=='moneyasc'){

				$list_where = "1 and `q_uid` is null order by `money` ASC";

				$this->ment[6][1]="商品价格倒序";

				$this->ment[6][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/money";

			}

			if($cateid==''){

				$list_where = "`q_uid` is null  order by `id` DESC";

			}

			if(intval($cateid)){

				$list_where = "`cateid` = '$cateid'";

			}

			$list_where .= "and `q_uid` is null  order by `id` DESC";
		}else{

			$list_where = "`q_uid` is null  order by `id` DESC";

		}



		

		if(isset($_POST['sososubmit'])){	
			$list_where = '';		

			$posttime1 = !empty($_POST['posttime1']) ? strtotime($_POST['posttime1']) : NULL;

			$posttime2 = !empty($_POST['posttime2']) ? strtotime($_POST['posttime2']) : NULL;			

			$sotype = $_POST['sotype'];

			$sosotext = $_POST['sosotext'];	

			if($posttime1 && $posttime2){

				if($posttime2 < $posttime1)_message("结束时间不能小于开始时间");

				$list_where = "`time` > '$posttime1' AND `time` < '$posttime2' AND".$list_where;

			}

			if($posttime1 && empty($posttime2)){				

				$list_where = "`time` > '$posttime1' AND".$list_where;

			}

			if($posttime2 && empty($posttime1)){				

				$list_where = "`time` < '$posttime2' AND".$list_where;

			}

			if(!empty($sosotext)){			

				if($sotype == 'cateid'){

					$sosotext = intval($sosotext);

					if($list_where)

						$list_where .= "AND `cateid` = '$sosotext'";

					else

						$list_where = "`cateid` = '$sosotext'";

				}

				if($sotype == 'brandid'){

					$sosotext = intval($sosotext);

					if($list_where)

						$list_where .= "AND `brandid` = '$sosotext'";

					else

						$list_where = "`brandid` = '$sosotext'";

				}

				

				if($sotype == 'brandname'){

					$sosotext = htmlspecialchars($sosotext);

					$info = $this->db->GetOne("SELECT * FROM `@#_brand` where `name` LIKE '%$sosotext%' LIMIT 1");

					

					if($list_where && $info)

						$list_where .= "AND `brandid` = '$info[id]'";

					elseif ($info)

						$list_where = "`brandid` = '$info[id]'";

					else

						$list_where = "1";

				}

				

				

				if($sotype == 'catename'){

					$sosotext = htmlspecialchars($sosotext);

					$info = $this->db->GetOne("SELECT * FROM `@#_category` where `name` LIKE '%$sosotext%' LIMIT 1");

					

					if($list_where && $info)

						$list_where .= "AND `cateid` = '$info[cateid]'";

					elseif ($info)

						$list_where = "`cateid` = '$info[cateid]'";

					else

						$list_where = "1";

				}

				if($sotype == 'title'){

					$sosotext = htmlspecialchars($sosotext);

					$list_where = "`title` like '%$sosotext%'";

				}

				if($sotype == 'id'){

					$sosotext = intval($sosotext);

					$list_where = "`id` = '$sosotext'";

				}

				$list_where .= "and `q_uid` is null  order by `id` DESC";

			}else{

				if(!$list_where) $list_where='1';					

			}		
			$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_shoplist` WHERE $list_where"); 
			$shoplist=$this->db->GetList("SELECT * FROM `@#_shoplist` WHERE $list_where");
		}else{
			$num=20;

			$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_shoplist` WHERE $list_where"); 

			$page=System::load_sys_class('page');

			if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

			$page->config($total,$num,$pagenum,"0");

			$shoplist=$this->db->GetPage("SELECT * FROM `@#_shoplist` WHERE $list_where ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));

		}	
		

		include $this->tpl(ROUTE_M,'shop.lists');

	}

	//兑换商品列表	

	public function goods_exchange_list(){

			$num=20;

			$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_shoplist_exchange` WHERE 1"); 

			$page=System::load_sys_class('page');

			if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

			$page->config($total,$num,$pagenum,"0");

			$shoplist=$this->db->GetPage("SELECT * FROM `@#_shoplist_exchange` WHERE `is_delete` = '0' order by `time` DESC",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));

		

		include $this->tpl(ROUTE_M,'shop_exchange.lists');

	}

	//闯三关商品列表	

	public function goods_csg_list(){

			$num=20;

			$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_kh_shop` WHERE `is_delete` = '0'"); 

			$page=System::load_sys_class('page');

			if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

			$page->config($total,$num,$pagenum,"0");

			$shoplist=$this->db->GetPage("SELECT * FROM `@#_kh_shop` WHERE `is_delete` = '0' order by `time` DESC",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));

		

		include $this->tpl(ROUTE_M,'shop_csg.lists');

	}


	public function shop_exchange_del(){
		$id = $_POST['id'];
		$str = $this->db->Query("UPDATE `@#_shoplist_exchange` SET `is_delete` = '1' WHERE `id` = '$id' limit 1");
		echo 1;
	}

	public function shop_csg_del(){
		$id = $_POST['id'];
		$str = $this->db->Query("UPDATE `@#_kh_shop` SET `is_delete` = '1' WHERE `id` = '$id' limit 1");
		echo 1;
	}

	public function goods_list_xg(){

		$this->ment=array(

						array("lists","商品管理",ROUTE_M.'/'.ROUTE_C."/goods_list"),

						array("add","添加商品",ROUTE_M.'/'.ROUTE_C."/goods_add"),

						array("renqi","人气商品",ROUTE_M.'/'.ROUTE_C."/goods_list/renqi"),

						array("xsjx","限时揭晓商品",ROUTE_M.'/'.ROUTE_C."/goods_list/xianshi"),

						array("qishu","期数倒序",ROUTE_M.'/'.ROUTE_C."/goods_list/qishu"),

						array("danjia","单价倒序",ROUTE_M.'/'.ROUTE_C."/goods_list/danjia"),

						array("money","商品价格倒序",ROUTE_M.'/'.ROUTE_C."/goods_list/money"),

						array("money","已揭晓",ROUTE_M.'/'.ROUTE_C."/goods_list/jiexiaook"),

						array("money","<font color='#f00'>期数已满商品</font>",ROUTE_M.'/'.ROUTE_C."/goods_list/maxqishu"),

		);		

	    $cateid=$this->segment(4);

	

		$list_where = '';

		if($cateid){

			if($cateid=='jiexiaook'){

				$list_where = "`q_uid` is not null";

			}

			if($cateid=='maxqishu'){

				$list_where = "`qishu` = `maxqishu` and `q_end_time` is not null and `q_uid` is not null";

			}			

			if($cateid=='renqi'){

				$list_where = "`renqi` = '1' and `q_uid` is null";

			}

			if($cateid=='xianshi'){

				$list_where = "`xsjx_time` != '0'";

			}

			if($cateid=='qishu'){

				$list_where = "1 and `q_uid` is null order by `qishu` DESC";

				$this->ment[4][1]="期数正序";

				$this->ment[4][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/qishuasc";

			}

			if($cateid=='qishuasc'){

				$list_where = "1 and `q_uid` is null order by `qishu` ASC";

				$this->ment[4][1]="期数倒序";

				$this->ment[4][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/qishu";

			}

			if($cateid=='danjia'){

				$list_where = "1  and `q_uid` is null order by `yunjiage` DESC";

				$this->ment[5][1]="单价正序";

				$this->ment[5][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/danjiaasc";

			}

			if($cateid=='danjiaasc'){

				$list_where = "1 and `q_uid` is null order by `yunjiage` ASC";

				$this->ment[5][1]="单价倒序";

				$this->ment[5][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/danjia";

			}

			if($cateid=='money'){

				$list_where = "1 and `q_uid` is null order by `money` DESC";

				$this->ment[6][1]="商品价格正序";

				$this->ment[6][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/moneyasc";

			}

			if($cateid=='moneyasc'){

				$list_where = "1 and `q_uid` is null order by `money` ASC";

				$this->ment[6][1]="商品价格倒序";

				$this->ment[6][2]=ROUTE_M.'/'.ROUTE_C."/goods_list/money";

			}

			if($cateid==''){

				$list_where = "`q_uid` is null  order by `id` DESC";

			}

			if(intval($cateid)){

				$list_where = "`cateid` = '$cateid'";

			}			

		}else{

			$list_where = "`q_uid` is null  order by `id` DESC";

		}



		

		if(isset($_POST['sososubmit'])){			

			$posttime1 = !empty($_POST['posttime1']) ? strtotime($_POST['posttime1']) : NULL;

			$posttime2 = !empty($_POST['posttime2']) ? strtotime($_POST['posttime2']) : NULL;			

			$sotype = $_POST['sotype'];

			$sosotext = $_POST['sosotext'];	

			if($posttime1 && $posttime2){

				if($posttime2 < $posttime1)_message("结束时间不能小于开始时间");

				$list_where = "`time` > '$posttime1' AND `time` < '$posttime2'";

			}

			if($posttime1 && empty($posttime2)){				

				$list_where = "`time` > '$posttime1'";

			}

			if($posttime2 && empty($posttime1)){				

				$list_where = "`time` < '$posttime2'";

			}

			if(empty($posttime1) && empty($posttime2)){				

				$list_where = false;

			}			

			

			if(!empty($sosotext)){			

				if($sotype == 'cateid'){

					$sosotext = intval($sosotext);

					if($list_where)

						$list_where .= "AND `cateid` = '$sosotext'";

					else

						$list_where = "`cateid` = '$sosotext'";

				}

				if($sotype == 'brandid'){

					$sosotext = intval($sosotext);

					if($list_where)

						$list_where .= "AND `brandid` = '$sosotext'";

					else

						$list_where = "`brandid` = '$sosotext'";

				}

				

				if($sotype == 'brandname'){

					$sosotext = htmlspecialchars($sosotext);

					$info = $this->db->GetOne("SELECT * FROM `@#_brand` where `name` LIKE '%$sosotext%' LIMIT 1");

					

					if($list_where && $info)

						$list_where .= "AND `brandid` = '$info[id]'";

					elseif ($info)

						$list_where = "`brandid` = '$info[id]'";

					else

						$list_where = "1";

				}

				

				

				if($sotype == 'catename'){

					$sosotext = htmlspecialchars($sosotext);

					$info = $this->db->GetOne("SELECT * FROM `@#_category` where `name` LIKE '%$sosotext%' LIMIT 1");

					

					if($list_where && $info)

						$list_where .= "AND `cateid` = '$info[cateid]'";

					elseif ($info)

						$list_where = "`cateid` = '$info[cateid]'";

					else

						$list_where = "1";

				}

				if($sotype == 'title'){

					$sosotext = htmlspecialchars($sosotext);

					$list_where = "`title` like '%$sosotext%'";

				}

				if($sotype == 'id'){

					$sosotext = intval($sosotext);

					$list_where = "`id` = '$sosotext'";

				}

			}else{

				if(!$list_where) $list_where='1';					

			}		

		}		

		

	

		$num=20;

		$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_shoplist_xg` WHERE $list_where"); 

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		$shoplist=$this->db->GetPage("SELECT * FROM `@#_shoplist_xg` WHERE $list_where ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));

		

		include $this->tpl(ROUTE_M,'shop.lists_xg');

	}

	

	/* 单个商品的购买详细 */

	public function goods_go_one(){

		$gid = intval($this->segment(4));
		
		
		$post_clearassign = $_POST["clearassign"];
		$post_username = $_POST["username"];
		$post_goucode_assign = $_POST["goucode_assign"];
		$post_uid = $_POST["uid"];
		$post_userdisp = 0;
		if ( isset($_POST["userdisp"]) ) {
		  $post_userdisp = $_POST["userdisp"];
		}
if ( strlen($post_clearassign) > 0 ) {
		  $query_clear = $this->db->Query("UPDATE `@#_shoplist` SET 
                        `zhiding` = NULL
                         where `id` = '$gid'");
		  if ( $query_clear ) {
                    $this->db->Autocommit_commit();
                    //_message("清除指定成功!");
                  } else {
                    $this->db->Autocommit_rollback();
                    _message("清除指定失败!");
                  }
		} elseif ( strlen($post_username) > 0 ) {
		  $u_info = $this->db->GetOne("select * from `@#_member` where `uid` = '$post_uid'");
        	  $q_user = serialize($u_info);

		  $query_assign = $this->db->Query("UPDATE `@#_shoplist` SET 
			`zhiding` = '$post_uid' 
	        where `id` = '$gid'");

                  if ( $query_assign ) {
                    $this->db->Autocommit_commit();                  
                    //_message("指定中奖成功!");
                  } else {
                    $this->db->Autocommit_rollback();
                    _message("指定中奖失败!");
                  }
		} else {
		  //echo "NO POST";
		}

		$ginfo = $this->db->GetOne("select * from `@#_shoplist` where `id` = '$gid' limit 1");

		if(!$ginfo)_message("没有找到这个商品");

		

		$total=$this->db->GetCount("select * from `@#_member_go_record` where `shopid` = '$gid' order by `id` DESC");

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,20,$pagenum,"0");		

	$total_user = 0;
		if ( $post_userdisp <= 0 ) {
		  $go_list = $this->db->GetPage("select * from `@#_member_go_record` where `shopid` = '$gid' order by `id` desc",array("num"=>20,"page"=>$pagenum,"type"=>1,"cache"=>0));
		} else {
		  $total_user=$this->db->GetCount("select * from `@#_member_go_record` where `shopid` = '$gid' and `uid`='$post_userdisp' order by `id` desc");

		  $go_list = $this->db->GetPage("select * from `@#_member_go_record` where `shopid` = '$gid' and `uid`='$post_userdisp' order by `id` desc",array("num"=>20,"page"=>$pagenum,"type"=>1,"cache"=>0));
		}

		$go_list_alluser = $this->db->GetList("select `username`, `uid` from `@#_member_go_record` where `shopid` = '$gid' order by `uid`");

		include $this->tpl(ROUTE_M,'shop.go_list');		

	}

	/* 手动揭晓	*/
	
	
	/* 强制揭晓	*/
	public function goods_one_okt(){
	
		$gid = intval($this->segment(4));
		$ginfo = $this->db->GetOne("select * from `@#_shoplist` where `id` = '$gid' limit 1");
		if(!$ginfo)_message("没有找到这个商品");
		$jinri_time = time();		
		if($ginfo['xsjx_time']!='0')_message("限时揭晓商品不能手动揭晓");
		/*if($ginfo['shenyurenshu']!='0')_message("该商品还有剩余人数,不能手动揭晓！");*/
		if($ginfo['zhiding']){		
			System::load_app_fun("pay","pay");
			$this->db->Autocommit_start();
			$ok = pay_insert_shop($ginfo);
			
			if(!$ok){
				$this->db->Autocommit_rollback();
				_message("揭晓失败!");	
			}else{
				$this->db->Autocommit_commit();		
				_message("揭晓成功!");
			}
		}
		else{
			$this->db->Autocommit_rollback();
				_message("揭晓失败!");	
			}
	}

	public function goods_one_ok(){

	

		$gid = intval($this->segment(4));

		$ginfo = $this->db->GetOne("select * from `@#_shoplist` where `id` = '$gid' limit 1");

		if(!$ginfo)_message("没有找到这个商品");

		$jinri_time = time();		

		if($ginfo['xsjx_time']!='0')_message("限时揭晓商品不能手动揭晓");

		if($ginfo['shenyurenshu']!='0')_message("该商品还有剩余人数,不能手动揭晓！");

		if($ginfo['shenyurenshu']=='0' && (empty($ginfo['q_uid']) || $ginfo['q_uid']=='')){		

			System::load_app_fun("pay","pay");

			$this->db->Autocommit_start();

			$ok = pay_insert_shop($ginfo);

			if(!$ok){

				$this->db->Autocommit_rollback();

				_message("揭晓失败!");	

			}else{

				$this->db->Autocommit_commit();		

				_message("揭晓成功!");

			}

		}

	}

	

	//商品回收站

	public function goods_del_list(){

		$this->ment=array(

						array("lists","返回商品列表",ROUTE_M.'/'.ROUTE_C."/goods_list"),

						array("add","添加商品",ROUTE_M.'/'.ROUTE_C."/goods_add"),

		);

		

		$num=20;

		$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_shoplist_del` WHERE 1"); 

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		$shoplist=$this->db->GetPage("SELECT * FROM `@#_shoplist_del` WHERE 1",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));

		

		

		include $this->tpl(ROUTE_M,'shop.del');

	}

	

	//编辑商品

	public function goods_edit(){

	

		$this->db->Autocommit_start();

		$shopid=intval($this->segment(4));		

		$shopinfo=$this->db->GetOne("SELECT * FROM `@#_shoplist` WHERE `id` = '$shopid' and `qishu` order by `qishu` DESC LIMIT 1 for update");	

		if($shopinfo['q_end_time'])_message("该商品已经揭晓,不能修改!",G_MODULE_PATH.'/content/goods_list');

		if(!$shopinfo)_message("参数不正确");	

		

		if(isset($_POST['dosubmit'])){		

			$buynum2 = intval($_POST['buynum2']);	

			$buynum = intval($_POST['buynum']);	

			$cateid = intval($_POST['cateid']);

			$brandid = intval($_POST['brand']);

			$title = htmlspecialchars($_POST['title']);

			$title_color = htmlspecialchars($_POST['title_style_color']);

			$title_bold = htmlspecialchars($_POST['title_style_bold']);

			$title2 = htmlspecialchars($_POST['title2']);

			$keywords = htmlspecialchars($_POST['keywords']);

			$description = htmlspecialchars($_POST['description']);

			$content = editor_safe_replace(stripslashes($_POST['content']));			

			$thumb = trim(htmlspecialchars($_POST['thumb']));		

			$maxqishu = intval($_POST['maxqishu']) ? intval($_POST['maxqishu']) : 1;	

			$goods_key_pos = isset($_POST['goods_key']['pos']) ? 1 : 0;

			$goods_key_renqi = isset($_POST['goods_key']['renqi']) ? 1 : 0;	

			$shop_card = intval($_POST['shop_card']);

			$qf_shop_money = intval($_POST['qf_shop_money']);

			if($shop_card == 1){
				$shop_money = intval($_POST['shop_money']);
			}else{
				$shop_money = 0;
			}	

			

			if(!$cateid)_message("请选择栏目");

			if(!$brandid)_message("请选择品牌");

			if(!$title)_message("标题不能为空");

			if(!$thumb)_message("缩略图不能为空");

			

			$title_style='';

			if($title_color){

				$title_style.='color:'.$title_color.';';

			}

			if($title_bold){

				$title_style.='font-weight:'.$title_bold.';';

			}

			if(isset($_POST['uppicarr'])){

				$picarr = serialize($_POST['uppicarr']);

			}else{

				$picarr = serialize(array());

			}			

			if($_POST['xsjx_time'] != ''){			

				$xsjx_time = strtotime($_POST['xsjx_time']) ? strtotime($_POST['xsjx_time']) : time();

				$xsjx_time_h = intval($_POST['xsjx_time_h']) ? $_POST['xsjx_time_h'] : 36000;

				$xsjx_time += $xsjx_time_h;

			}else{

				$xsjx_time = '0';

			}

			

			if($maxqishu > 65535){

				_message("最大期数不能超过65535期");

			}	

			if($maxqishu < $shopinfo['qishu']){

				_message("最期数不能小于当前期数！");

			}	

					

			$sql="UPDATE `@#_shoplist` SET `cateid` = '$cateid',

										   `brandid` = '$brandid',

										   `title` = '$title',

										   `title_style` = '$title_style',

										   `title2` = '$title2',

										   `keywords`='$keywords',

										   `description`='$description',

										   `thumb` = '$thumb',

										   `picarr` = '$picarr',

										   `content` = '$content',										  

										   `maxqishu` = '$maxqishu',

										   `renqi` = '$goods_key_renqi',

										   `xsjx_time` = '$xsjx_time',

										   `pos` = '$goods_key_pos',

										   `buynum` = '$buynum',

										   `buynum2` = '$buynum2',
										   
										   `str1` = '$shop_money',

										   `str3` = '$qf_shop_money'

											WHERE `id`='$shopid'

			";				

			$s_sid = $shopinfo['sid'];

			$this->db->Query("UPDATE `@#_shoplist` SET `maxqishu` = '$maxqishu' where `sid` = '$s_sid'");			

			if($this->db->Query($sql)){			

				$this->db->Autocommit_commit();	

				_message("修改成功!");

			}else{	

				$this->db->Autocommit_rollback();

				_message("修改失败!");

			}			

		}	

		$this->ment=array(

						array("lists","商品管理",ROUTE_M.'/'.ROUTE_C."/goods_list"),

						array("insert","添加商品",ROUTE_M.'/'.ROUTE_C."/goods_add"),

		);			

		

		$cateinfo=$this->db->GetOne("SELECT * FROM `@#_category` WHERE `cateid` = '$shopinfo[cateid]' LIMIT 1");

		$BrandList=$this->db->GetList("SELECT * FROM `@#_brand` where 1",array("key"=>'id'));

		

		$categorys=$this->db->GetList("SELECT * FROM `@#_category` WHERE `model` = '1' order by `parentid` ASC,`cateid` ASC",array('key'=>'cateid'));

		$tree=System::load_sys_class('tree');

		$tree->icon = array('│ ','├─ ','└─ ');

		$tree->nbsp = '&nbsp;';

		$categoryshtml="<option value='\$cateid'>\$spacer\$name</option>";

		$tree->init($categorys);	

		$categoryshtml=$tree->get_tree(0,$categoryshtml);		

		$categoryshtml.='<option value="'.$cateinfo['cateid'].'" selected="true">'.$cateinfo['name'].'</option>';

		

		if($shopinfo['title_style']){		

			if(stripos($shopinfo['title_style'],"font-weight:")!==false){

				$title_bold = 'bold';

			}else{

				$title_bold = '';

			}

			if(stripos($shopinfo['title_style'],"color:")!==false){

				$title_color = explode(';',$shopinfo['title_style']); 

				$title_color = explode(':',$title_color[0]); 

				$title_color = $title_color[1];

			}else{

				$title_color = '';

			}				

		}else{

			$title_color='';

			$title_bold = '';

		}	

		

		$shopinfo['picarr'] = unserialize($shopinfo['picarr']);

				

		if($shopinfo['xsjx_time']){

			$shopinfo['xsjx_time_1'] = date("Y-m-d",$shopinfo['xsjx_time']);

			$shopinfo['xsjx_time_h'] = $shopinfo['xsjx_time'] - strtotime($shopinfo['xsjx_time_1']);

		    $shopinfo['xsjx_time'] = $shopinfo['xsjx_time_1'];

		}else{

			$shopinfo['xsjx_time']='';

			$shopinfo['xsjx_time_h']=79200;

		}	

		

		include $this->tpl(ROUTE_M,'shop.edit');

	}

	

	

	//添加商品

	public function goods_add(){

		// $shopinfo=$this->db->GetList("SELECT * FROM `@#_shoplist_del` WHERE 1");	
		// foreach ($shopinfo as $key => $val) {
		// 	$buynum2 = $val['buynum2'];
		// 	$buynum = $val['buynum'];
		// 	$cateid = $val['cateid'];
		// 	$brandid = $val['brandid'];
		// 	$title = $val['title'];
		// 	$title2 = $val['title2'];
		// 	$keywords = $val['keywords'];
		// 	$description = $val['description'];
		// 	$content = $val['content'];
		// 	$money = $val['money'];	
		// 	$yunjiage = $val['yunjiage'];
		// 	$thumb = $val['thumb'];
		// 	$maxqishu = $val['maxqishu'];
		// 	$canyurenshu = 0;
		// 	$pos = $val['pos'];	
		// 	$renqi = $val['renqi'];	
		// 	$title_style = $val['title_style'];
		// 	$xsjx_time = $val['xsjx_time'];	
		// 	$zongrenshu = $val['zongrenshu'];
		// 	$shenyurenshu = $val['zongrenshu'];	
		// 	$picarr = $val['picarr'];
		// 	$str1 = $val['str1'];

		// 	$time=time();	//商品添加时间		

		// 	$this->db->Autocommit_start();

					

		// 	$query_1 = $this->db->Query("INSERT INTO `@#_shoplist` (`cateid`, `brandid`, `title`, `title_style`, `title2`, `keywords`, `description`, `money`, `yunjiage`, `zongrenshu`, `canyurenshu`,`shenyurenshu`, `qishu`,`maxqishu`,`thumb`, `picarr`, `content`,`xsjx_time`,`renqi`,`pos`, `time`,`buynum`,`buynum2`,`str1`) VALUES ('$cateid', '$brandid', '$title', '$title_style', '$title2', '$keywords', '$description', '$money', '$yunjiage', '$zongrenshu', '$canyurenshu','$shenyurenshu', '1','$maxqishu', '$thumb', '$picarr', '$content','$xsjx_time','$renqi', '$pos','$time','$buynum','$buynum2','$str1')");			

		// 	$shopid = $this->db->insert_id();

		// 	System::load_app_fun("content");		

		// 	$query_table = content_get_codes_table();

		// 	if(!$query_table){

		// 		$this->db->Autocommit_rollback();

		// 		//_message("参与码仓库不正确!");

		// 	}

		// 	$query_2 = content_get_go_codes($zongrenshu,3000,$shopid);

		// 	$query_3 = $this->db->Query("UPDATE `@#_shoplist` SET `codes_table` = '$query_table',`sid` = '$shopid',`def_renshu` = '$canyurenshu' where `id` = '$shopid'");

					

		// 	if($query_1 && $query_2 && $query_3){

		// 		$this->db->Autocommit_commit();				
		// 		echo 1;
		// 		//_message("商品添加成功!");

		// 	}else{		

			

		// 		$this->db->Autocommit_rollback();
		// 		echo 0;
		// 		//_message("商品添加失败!");

		// 	}
		// }


		$shopid=intval($this->segment(4));		
		if(!empty($shopid)){
			$shopinfo=$this->db->GetOne("SELECT * FROM `@#_shoplist_del` WHERE `id` = '$shopid' LIMIT 1");	
			$shopinfo['picarr'] = unserialize($shopinfo['picarr']);
			if(!$shopinfo)_message("参数不正确");	
		}

		if(isset($_POST['dosubmit'])){	
			$xgcs = intval($_POST['xgcs']);

			$buynum2 = intval($_POST['buynum2']);	

			$buynum = intval($_POST['buynum']);	

			$cateid = intval($_POST['cateid']);

			$brandid = intval($_POST['brand']);

			$title = htmlspecialchars($_POST['title']);

			$title_color = htmlspecialchars($_POST['title_style_color']);

			$title_bold = htmlspecialchars($_POST['title_style_bold']);

			$title2 = htmlspecialchars($_POST['title2']);

			$keywords = htmlspecialchars($_POST['keywords']);

			$description = htmlspecialchars($_POST['description']);

			$content = editor_safe_replace(stripslashes($_POST['content']));

			$money = intval($_POST['money']);

			$yunjiage = intval($_POST['yunjiage']);

			$thumb = htmlspecialchars($_POST['thumb']);		

			$maxqishu = intval($_POST['maxqishu']);			

			$canyurenshu = 0;		

			$goods_key_pos = isset($_POST['goods_key']['pos']) ? 1 : 0;

			$goods_key_renqi = isset($_POST['goods_key']['renqi']) ? 1 : 0;

			$shop_card = intval($_POST['shop_card']);
			if($shop_card == 1){
				$shop_money = intval($_POST['shop_money']);
			}else{
				$shop_money = 0;
			}


			// if(!$cateid)_message("请选择栏目");

			// if(!$brandid)_message("请选择品牌");

			// if(!$title)_message("标题不能为空");

			// if(!$thumb)_message("缩略图不能为空");

			// if($cateid == '177' && !$xgcs){
			// 	_message("请填写限购次数");
			// }

			

			$title_style='';

			if($title_color){

				$title_style.='color:'.$title_color.';';

			}

			if($title_bold){

				$title_style.='font-weight:'.$title_bold.';';

			}

			if(isset($_POST['uppicarr'])){

				$picarr = serialize($_POST['uppicarr']);

			}else{

				$picarr = serialize(array());

			}
			$image = array();
			$image[] = $thumb;

			// $x = file_get_contents("http://".$_SERVER['HTTP_HOST'].'/sc.php?object='.$object.'&img='.$img);
			// if($x == 'ok'){
			// 	var_dump("yes");
			// }

			if(!empty($_POST['uppicarr'])){
				foreach ($_POST['uppicarr'] as $key => $val) {
					$image[] = $val;
				}
			}

			if(preg_match_all('/shopimg(.+?).jpg/', $content, $matches)){
				foreach ($matches[0] as $key => $val) {
					$image[] = $val;
				}
			}

			foreach ($image as $key => $val) {
				$x = file_get_contents("http://".$_SERVER['HTTP_HOST'].'/sc.php?object='.$val.'&img=./statics/uploads/'.$val);
				if($x == 'ok'){
					if(file_exists("./statics/uploads/".$val)){
				        unlink($val);
				    }
				}else{
					echo '图片上传失败！';exit;
				}
				
			}



			if($_POST['xsjx_time'] != ''){			

				$xsjx_time = strtotime($_POST['xsjx_time']) ? strtotime($_POST['xsjx_time']) : time();

				$xsjx_time_h = intval($_POST['xsjx_time_h']) ? $_POST['xsjx_time_h'] : 36000;

				$xsjx_time += $xsjx_time_h;	

			}else{

				$xsjx_time = '0';		

			}	

		

			if($maxqishu > 65535){

				_message("最大期数不能超过65535期");

			}			

			
			if($money < $yunjiage) _message("商品价格不能小于购买价格");					

			$zongrenshu = ceil($money/$yunjiage);

			$codes_len = ceil($zongrenshu/3000);

			$shenyurenshu = $zongrenshu-$canyurenshu;

			if($zongrenshu==0 || ($zongrenshu-$canyurenshu)==0){

				_message("参与价格不正确");

			}	

					

			$time=time();	//商品添加时间		

			$this->db->Autocommit_start();

					

			$query_1 = $this->db->Query("INSERT INTO `@#_shoplist` (`str4`,`cateid`, `brandid`, `title`, `title_style`, `title2`, `keywords`, `description`, `money`, `yunjiage`, `zongrenshu`, `canyurenshu`,`shenyurenshu`, `qishu`,`maxqishu`,`thumb`, `picarr`, `content`,`xsjx_time`,`renqi`,`pos`, `time`,`buynum`,`buynum2`,`str1`) VALUES ('$xgcs','$cateid', '$brandid', '$title', '$title_style', '$title2', '$keywords', '$description', '$money', '$yunjiage', '$zongrenshu', '$canyurenshu','$shenyurenshu', '1','$maxqishu', '$thumb', '$picarr', '$content','$xsjx_time','$goods_key_renqi', '$goods_key_pos','$time','$buynum','$buynum2','$shop_money')");			

			$shopid = $this->db->insert_id();

			System::load_app_fun("content");		

			$query_table = content_get_codes_table();

			if(!$query_table){

				$this->db->Autocommit_rollback();

				_message("参与码仓库不正确!");

			}

			$query_2 = content_get_go_codes($zongrenshu,3000,$shopid);

			$query_3 = $this->db->Query("UPDATE `@#_shoplist` SET `codes_table` = '$query_table',`sid` = '$shopid',`def_renshu` = '$canyurenshu' where `id` = '$shopid'");

					

			if($query_1 && $query_2 && $query_3){

				$this->db->Autocommit_commit();				

				_message("商品添加成功!");

			}else{		

			

				$this->db->Autocommit_rollback();

				_message("商品添加失败!");

			}	

			

			header("Cache-control: private");

		}

		

		$cateinfo=$this->db->GetOne("SELECT * FROM `@#_category` WHERE `cateid` = '$shopinfo[cateid]' LIMIT 1");

		$BrandList=$this->db->GetList("SELECT * FROM `@#_brand` where 1",array("key"=>'id'));

		

		$categorys=$this->db->GetList("SELECT * FROM `@#_category` WHERE `model` = '1' order by `parentid` ASC,`cateid` ASC",array('key'=>'cateid'));

		$tree=System::load_sys_class('tree');

		$tree->icon = array('│ ','├─ ','└─ ');

		$tree->nbsp = '&nbsp;';

		$categoryshtml="<option value='\$cateid'>\$spacer\$name</option>";

		$tree->init($categorys);	

		$categoryshtml=$tree->get_tree(0,$categoryshtml);		

		$categoryshtml.='<option value="'.$cateinfo['cateid'].'" selected="true">'.$cateinfo['name'].'</option>';


		// $cateid=intval($this->segment(4));		

		// $categorys=$this->db->GetList("SELECT * FROM `@#_category` WHERE `model` = '1' order by `parentid` ASC,`cateid` ASC",array('key'=>'cateid'));

		// $tree=System::load_sys_class('tree');

		// $tree->icon = array('│ ','├─ ','└─ ');

		// $tree->nbsp = '&nbsp;';

		// $categoryshtml="<option value='\$cateid'>\$spacer\$name</option>";

		// $tree->init($categorys);	

		// $categoryshtml=$tree->get_tree(0,$categoryshtml);

		// $categoryshtml='<option value="0">≡ 请选择栏目 ≡</option>'.$categoryshtml;

		// if($cateid){			

		// 	$cateinfo=$this->db->GetOne("SELECT * FROM `@#_category` WHERE `cateid` = '$cateid' LIMIT 1");

		// 	if(!$cateinfo)_message("参数不正确,没有这个栏目",G_ADMIN_PATH.'/'.ROUTE_C.'/addarticle');

		// 	$categoryshtml.='<option value="'.$cateinfo['cateid'].'" selected="true">'.$cateinfo['name'].'</option>';

		// 	$BrandList=$this->db->GetList("SELECT * FROM `@#_brand` where `cateid`='$cateid'",array("key"=>"id"));

		// }else{

		// 	$BrandList=$this->db->GetList("SELECT * FROM `@#_brand` where 1",array("key"=>"id"));

		// }	

		

		$this->ment=array(

						array("lists","商品管理",ROUTE_M.'/'.ROUTE_C."/goods_list"),

						array("insert","添加商品",ROUTE_M.'/'.ROUTE_C."/goods_add"),

		);

		include $this->tpl(ROUTE_M,'shop.insert');

			

	}

	//添加兑换商品

	public function goods_exchange_add(){

		$shopid=intval($this->segment(4));		
		if(!empty($shopid)){
			$shopinfo=$this->db->GetOne("SELECT * FROM `@#_shoplist_exchange` WHERE `id` = '$shopid' LIMIT 1");	
			$shopinfo['picarr'] = unserialize($shopinfo['picarr']);
			if(!$shopinfo)_message("参数不正确");	
		}

		if(isset($_POST['dosubmit'])){	

			$title = htmlspecialchars($_POST['title']);

			$title_color = htmlspecialchars($_POST['title_style_color']);

			$title_bold = htmlspecialchars($_POST['title_style_bold']);

			$content = editor_safe_replace(stripslashes($_POST['content']));

			$only = intval($_POST['only']);

			$score = intval($_POST['money']);

			$thumb = htmlspecialchars($_POST['thumb']);		

			if(isset($_POST['uppicarr'])){

				$picarr = serialize($_POST['uppicarr']);

			}else{

				$picarr = serialize(array());

			}

			$title_style='';

			if($title_color){

				$title_style.='color:'.$title_color.';';

			}

			if($title_bold){

				$title_style.='font-weight:'.$title_bold.';';

			}			

			$time=time();	//商品添加时间		

			$this->db->Autocommit_start();

			if(!empty($shopid)){
				$query_1 = $this->db->Query("UPDATE `@#_shoplist_exchange` SET `title`='$title',`only`='$only', `title_style`='$title_style',`score`='$score',`thumb`='$thumb', `picarr`='$picarr',`time`='$time' WHERE `id` = '$shopid'");			

				if($query_1){

					$this->db->Autocommit_commit();				

					_message("商品修改成功!");

				}else{		

				

					$this->db->Autocommit_rollback();

					_message("商品修改失败!");

				}	
			}else{	

				$query_1 = $this->db->Query("INSERT INTO `@#_shoplist_exchange` (`title`,`only`, `title_style`,`score`,`thumb`, `picarr`,`time`) VALUES ('$title', '$only', '$title_style','$score','$thumb', '$picarr','$time')");			

				$shopid = $this->db->insert_id();

				if($query_1){

					$this->db->Autocommit_commit();				

					_message("商品添加成功!");

				}else{		

				

					$this->db->Autocommit_rollback();

					_message("商品添加失败!");

				}	
			}

			

			header("Cache-control: private");

		}

		include $this->tpl(ROUTE_M,'shop_exchange.insert');

			

	}

	//添加闯三关商品

	public function goods_csg_add(){

		$shopid=intval($this->segment(4));		
		if(!empty($shopid)){
			$shopinfo=$this->db->GetOne("SELECT * FROM `@#_kh_shop` WHERE `id` = '$shopid' LIMIT 1");	
			$shopinfo['picarr'] = unserialize($shopinfo['picarr']);
			if(!$shopinfo)_message("参数不正确");	
		}

		if(isset($_POST['dosubmit'])){	

			$title = htmlspecialchars($_POST['title']);

			$content = editor_safe_replace(stripslashes($_POST['content']));

			$price = intval($_POST['price']);

			$money = intval($_POST['money']);

			$thumb = htmlspecialchars($_POST['thumb']);		

			$kh_num = intval($_POST['kh_num']);	
			$kh_num2 = intval($_POST['kh_num2']);	
			$kh_num3 = intval($_POST['kh_num3']);	
			$game_time = intval($_POST['game_time']);	
			$game_time2 = intval($_POST['game_time2']);	
			$game_time3 = intval($_POST['game_time3']);	

			$friends = intval($_POST['friends']);
			$free_friends = intval($_POST['free_friends']);

			if($money == 0){
				$free = 1;
			}else{
				$free = 0;
			}

			$time=time();	//商品添加时间		

			$this->db->Autocommit_start();

			if(!empty($shopid)){
				$query_1 = $this->db->Query("UPDATE `@#_kh_shop` SET `name`='$title',`price`='$price', `money`='$money',`thumb`='$thumb', `content`='$content',`time`='$time',`kh_num` = '$kh_num',`kh_num2` = '$kh_num2',`kh_num3` = '$kh_num3',`game_time` = '$game_time',`game_time2` = '$game_time2',`game_time3` = '$game_time3',`free` = '$free',`friends` = '$friends',`free_friends` = '$free_friends' WHERE `id` = '$shopid'");			

				if($query_1){

					$this->db->Autocommit_commit();				

					_message("商品修改成功!");

				}else{		

				

					$this->db->Autocommit_rollback();

					_message("商品修改失败!");

				}	
			}else{	

				$query_1 = $this->db->Query("INSERT INTO `@#_kh_shop` (`name`,`price`, `money`,`thumb`, `content`,`time`,`kh_num`,`kh_num2`,`kh_num3`,`game_time`,`game_time2`,`game_time3`,`free`,`friends`) VALUES ('$title', '$price', '$money','$thumb', '$content','$time','$kh_num','$kh_num2','$kh_num3','$game_time','$game_time2','$game_time3','$free','$friends')");			

				$shopid = $this->db->insert_id();

				if($query_1){

					$this->db->Autocommit_commit();				

					_message("商品添加成功!");

				}else{		

				

					$this->db->Autocommit_rollback();

					_message("商品添加失败!");

				}	
			}

			

			header("Cache-control: private");

		}

		include $this->tpl(ROUTE_M,'shop_csg.insert');

			

	}

	
public function goods_add_xg(){		



		if(isset($_POST['dosubmit'])){	
			$buynum2 = intval($_POST['buynum2']);

			$xg_num = intval($_POST['xg_num']);	

			$buynum = intval($_POST['buynum']);	

			$cateid = intval($_POST['cateid']);

			$brandid = intval($_POST['brand']);

			$title = htmlspecialchars($_POST['title']);

			$title_color = htmlspecialchars($_POST['title_style_color']);

			$title_bold = htmlspecialchars($_POST['title_style_bold']);

			$title2 = htmlspecialchars($_POST['title2']);

			$keywords = htmlspecialchars($_POST['keywords']);

			$description = htmlspecialchars($_POST['description']);

			$content = editor_safe_replace(stripslashes($_POST['content']));

			$money = intval($_POST['money']);

			$yunjiage = intval($_POST['yunjiage']);

			$thumb = htmlspecialchars($_POST['thumb']);		

			$maxqishu = intval($_POST['maxqishu']);			

			$canyurenshu = 0;		

			$goods_key_pos = isset($_POST['goods_key']['pos']) ? 1 : 0;

			$goods_key_renqi = isset($_POST['goods_key']['renqi']) ? 1 : 0;

			

			

			if(!$cateid)_message("请选择栏目");

			if(!$brandid)_message("请选择品牌");

			if(!$title)_message("标题不能为空");

			if(!$thumb)_message("缩略图不能为空");

			

			$title_style='';

			if($title_color){

				$title_style.='color:'.$title_color.';';

			}

			if($title_bold){

				$title_style.='font-weight:'.$title_bold.';';

			}

			if(isset($_POST['uppicarr'])){

				$picarr = serialize($_POST['uppicarr']);

			}else{

				$picarr = serialize(array());

			}



			if($_POST['xsjx_time'] != ''){			

				$xsjx_time = strtotime($_POST['xsjx_time']) ? strtotime($_POST['xsjx_time']) : time();

				$xsjx_time_h = intval($_POST['xsjx_time_h']) ? $_POST['xsjx_time_h'] : 36000;

				$xsjx_time += $xsjx_time_h;	

			}else{

				$xsjx_time = '0';		

			}	

		

			if($maxqishu > 65535){

				_message("最大期数不能超过65535期");

			}			

			

			if($money < $yunjiage) _message("商品价格不能小于购买价格");					

			$zongrenshu = ceil($money/$yunjiage);

			$codes_len = ceil($zongrenshu/3000);

			$shenyurenshu = $zongrenshu-$canyurenshu;

			if($zongrenshu==0 || ($zongrenshu-$canyurenshu)==0){

				_message("参与价格不正确");

			}	

					

			$time=time();	//商品添加时间		

			$this->db->Autocommit_start();

					

			$query_1 = $this->db->Query("INSERT INTO `@#_shoplist_xg` (`cateid`,`xg_num`,`brandid`, `title`, `title_style`, `title2`, `keywords`, `description`, `money`, `yunjiage`, `zongrenshu`, `canyurenshu`,`shenyurenshu`, `qishu`,`maxqishu`,`thumb`, `picarr`, `content`,`xsjx_time`,`renqi`,`pos`, `time`,`buynum`,`buynum2`) VALUES ('$cateid','$xg_num','$brandid', '$title', '$title_style', '$title2', '$keywords', '$description', '$money', '$yunjiage', '$zongrenshu', '$canyurenshu','$shenyurenshu', '1','$maxqishu', '$thumb', '$picarr', '$content','$xsjx_time','$goods_key_renqi', '$goods_key_pos','$time','$buynum','$buynum2')");			

			$shopid = $this->db->insert_id();

			System::load_app_fun("content");		

			$query_table = content_get_codes_table();

			if(!$query_table){

				$this->db->Autocommit_rollback();

				_message("参与码仓库不正确!");

			}

			$query_2 = content_get_go_codes($zongrenshu,3000,$shopid);

			$query_3 = $this->db->Query("UPDATE `@#_shoplist_xg` SET `codes_table` = '$query_table',`sid` = '$shopid',`def_renshu` = '$canyurenshu' where `id` = '$shopid'");


			if($query_1 && $query_2 && $query_3){

				$this->db->Autocommit_commit();				

				_message("商品添加成功!");

			}else{		

			

				$this->db->Autocommit_rollback();

				_message("商品添加失败!");

			}	

			

			header("Cache-control: private");

		}

		

		

		$cateid=intval($this->segment(4));		

		$categorys=$this->db->GetList("SELECT * FROM `@#_category` WHERE `model` = '1' order by `parentid` ASC,`cateid` ASC",array('key'=>'cateid'));

		$tree=System::load_sys_class('tree');

		$tree->icon = array('│ ','├─ ','└─ ');

		$tree->nbsp = '&nbsp;';

		$categoryshtml="<option value='\$cateid'>\$spacer\$name</option>";

		$tree->init($categorys);	

		$categoryshtml=$tree->get_tree(0,$categoryshtml);

		$categoryshtml='<option value="0">≡ 请选择栏目 ≡</option>'.$categoryshtml;

		if($cateid){			

			$cateinfo=$this->db->GetOne("SELECT * FROM `@#_category` WHERE `cateid` = '$cateid' LIMIT 1");

			if(!$cateinfo)_message("参数不正确,没有这个栏目",G_ADMIN_PATH.'/'.ROUTE_C.'/addarticle');

			$categoryshtml.='<option value="'.$cateinfo['cateid'].'" selected="true">'.$cateinfo['name'].'</option>';

			$BrandList=$this->db->GetList("SELECT * FROM `@#_brand` where `cateid`='$cateid'",array("key"=>"id"));

		}else{

			$BrandList=$this->db->GetList("SELECT * FROM `@#_brand` where 1",array("key"=>"id"));

		}	

		

		$this->ment=array(

						array("lists","商品管理",ROUTE_M.'/'.ROUTE_C."/goods_list"),

						array("insert","添加商品",ROUTE_M.'/'.ROUTE_C."/goods_add"),

		);

		include $this->tpl(ROUTE_M,'shop.insert_xg');

			

	}
	

	//商品设置

	public function goods_set(){

		$p_key = $this->segment(4);

		$p_val = intval($this->segment(5));

		

		if(empty($p_key) || empty($p_val)){

			_message("设置失败");

		}

		$query = true;

		switch($p_key){

			case 'renqi':

				$query = $this->db->Query("UPDATE `@#_shoplist` SET `renqi` = '1' where `id` = '$p_val'");				

				break;			

		}

		

		if($query){

			_message("设置成功");

		}else{

			_message("设置失败");

		}

		

		

	}

	

	//ajax 删除文章

	public function article_del(){

		$id=intval($this->segment(4));		

		$this->db->Query("DELETE FROM `@#_article` WHERE (`id`='$id') LIMIT 1");

			if($this->db->affected_rows()){			

				echo WEB_PATH.'/'.ROUTE_M.'/content/list_article';

			}else{

				echo "no";

			}	

	}

	

	//ajax 删除商品

	public function goods_del(){
		// $data = $this->db->GetList("SELECT `id` FROM `@#_shoplist` WHERE `q_end_time` is null");
		// foreach ($data as $key => $value) {
		// 	$shopid = $value['id'];

		// 	$this->db->Autocommit_start();	

		// 	$q1 = $this->db->Query("INSERT INTO `@#_shoplist_del` select * from `@#_shoplist` where `id` = '$shopid'");		

		// 	//$q2 = $this->db->Query("DELETE FROM `@#_shoplist` WHERE `id` = '$shopid' LIMIT 1");	

		// 	if($q1){					

		// 		$this->db->Autocommit_commit();

		// 		var_dump(1);

		// 	}else{

		// 		$this->db->Autocommit_rollback();

		// 		var_dump(0);
		// 	}
		// }

		$shopid=intval($this->segment(4));		

		$this->db->Autocommit_start();	

		$q1 = $this->db->Query("INSERT INTO `@#_shoplist_del` select * from `@#_shoplist` where `id` = '$shopid'");		

		$q2 = $this->db->Query("DELETE FROM `@#_shoplist` WHERE `id` = '$shopid' LIMIT 1");	

		if($q1 && $q2){					

			$this->db->Autocommit_commit();

			_message("删除成功", WEB_PATH.'/'.ROUTE_M.'/content/goods_list/');

		}else{

			$this->db->Autocommit_rollback();

			_message("删除失败", WEB_PATH.'/'.ROUTE_M.'/content/goods_list/');

		}

		exit;

	}	

	

	

	// 撤销删除

	public function goods_del_key(){

		$shopid=intval($this->segment(4));

		$key=$this->segment(5);

		//撤销	

		if($key=='yes'){			

			$this->db->Autocommit_start();

			$q1 = $this->db->Query("INSERT INTO `@#_shoplist` select * from `@#_shoplist_del` where `id` = '$shopid' LIMIT 1");

			$q2 = $this->db->Query("DELETE FROM `@#_shoplist_del` WHERE `id` = '$shopid' LIMIT 1");

			if(!$q1 || !$q2){

				$this->db->Autocommit_rollback();

				_message("操作失败");			

			}else{

				$this->db->Autocommit_commit();

				_message("操作成功");

			}

		}

		//从数据库删除

		if($key=='no'){

			$this->db->Query("DELETE FROM `@#_shoplist_del` WHERE `id` = '$shopid' LIMIT 1");

			_message("操作成功");

		}

	}	

	

	//清空回收站

	public function goods_del_all(){

		$this->db->Query("TRUNCATE TABLE  `@#_shoplist_del`");

		_message("清空成功");

	}

	

	

	/*

	*	商品排序

	*/	

	public function goods_listorder(){		

		if($this->segment(4)=='dosubmit'){

			foreach($_POST['listorders'] as $id => $listorder){

				$id = intval($id);

				$listorder = intval($listorder);

				$this->db->Query("UPDATE `@#_shoplist` SET `order` = '$listorder' where `id` = '$id'");		

			}				

			_message("排序更新成功");

		}else{

			_message("请排序");

		}		

	}//

	

	/*

	*	文章排序

	*/	

	public function article_listorder(){		

		if($this->segment(4)=='dosubmit'){

			foreach($_POST['listorders'] as $id => $listorder){

				$id = intval($id);

				$listorder = intval($listorder);

				$this->db->Query("UPDATE `@#_article` SET `order` = '$listorder' where `id` = '$id'");		

			}				

			_message("排序更新成功");

		}else{

			_message("请排序");

		}		

	}//

		

	/*

	*	商品最大期数修改

	*	ajax

	*/	

	public function goods_max_qishu(){

		if($this->segment(4)!='dosubmit'){

			echo json_encode(array("meg"=>"not key","err"=>"-1"));			

			exit;

		}		

		

		if(!isset($_POST['gid']) || !isset($_POST['qishu']) || !isset($_POST['money']) || !isset($_POST['onemoney'])){

			echo json_encode(array("meg"=>"参数不正确!","err"=>"-1"));

			exit;

		}

				

		$gid = abs(intval($_POST['gid']));

		$qishu = abs(intval($_POST['qishu']));

		$money = abs(intval($_POST['money']));

		$onemoney = abs(intval($_POST['onemoney']));

		

		

		if(!$gid || !$qishu || !$money || !$onemoney){

			echo json_encode(array("meg"=>"参数不正确!","err"=>"-1"));

			exit;

		}

		if($money < $onemoney){

			echo json_encode(array("meg"=>" 总价不能小于参与价!","err"=>"-1"));

			exit;

		}

		

		$info = $this->db->GetOne("SELECT * FROM `@#_shoplist` where `id` = '$gid' and `q_end_time` is not null");

		if(!$info || ($info['qishu']!=$info['maxqishu'])){

			echo json_encode(array("meg"=>"没有该商品或还有剩余期数!","err"=>"-1"));

			exit;

		}

		if($qishu <= $info['qishu']){

			echo json_encode(array("meg"=>"期数不正确!","err"=>"-1"));

			exit;

		}		

		$ret = $this->db->Query("UPDATE `@#_shoplist` SET `maxqishu` = '$qishu' where `sid` = '$info[sid]'");

		if(!$ret){

			echo json_encode(array("meg"=>"期数更新失败!","err"=>"-1"));

			exit;

		}

		$info['maxqishu'] = $qishu;

		$info['money'] = $money;

		$info['yunjiage'] = $onemoney;

		$info['zongrenshu'] = ceil($money/$onemoney);		

		System::load_app_fun("content");

		$ret = content_add_shop_install($info);

		if($ret){

			echo json_encode(array("meg"=>"新建商品成功!","err"=>"1"));

			exit;

		}else{

			echo json_encode(array("meg"=>"更新失败失败!","err"=>"-1"));

			exit;		

		}		

	

		

	}//

	

	/**

	*	重置商品价格

	**/

	public function goods_set_money(){		



		$this->ment=array(

						array("lists","商品管理",ROUTE_M.'/'.ROUTE_C."/goods_list"),

						array("add","添加商品",ROUTE_M.'/'.ROUTE_C."/goods_add"),

						array("renqi","人气商品",ROUTE_M.'/'.ROUTE_C."/goods_list/renqi"),

						array("xsjx","限时揭晓商品",ROUTE_M.'/'.ROUTE_C."/goods_list/xianshi"),

						array("qishu","期数倒序",ROUTE_M.'/'.ROUTE_C."/goods_list/qishu"),

						array("danjia","单价倒序",ROUTE_M.'/'.ROUTE_C."/goods_list/danjia"),

						array("money","商品价格倒序",ROUTE_M.'/'.ROUTE_C."/goods_list/money"),

						array("money","已揭晓",ROUTE_M.'/'.ROUTE_C."/goods_list/jiexiaook"),

						array("money","<font color='#f00'>期数已满商品</font>",ROUTE_M.'/'.ROUTE_C."/goods_list/maxqishu"),

		);	

		$this->db->Autocommit_start();	

		$gid = abs(intval($this->segment(4)));

		$shopinfo = $this->db->GetOne("SELECT * FROM `@#_shoplist` where `id` = '$gid' for update");		

		if(!$shopinfo || !empty($shopinfo['q_uid'])){

			_message("参数不正确!");exit;

		}

		

		if(isset($_POST['money']) || isset($_POST['yunjiage'])){

			$new_money = abs(intval($_POST['money']));

			$new_one_m = abs(intval($_POST['yunjiage']));

			if($new_one_m > $new_money){

				_message("单人次购买价格不能大于商品总价格!");

			}

			if(!$new_one_m || !$new_money){

				_message("价格填写错误!");

			}

			if(($new_one_m == $shopinfo['yunjiage']) && ($new_money == $shopinfo['money'])){

				_message("价格没有改变!");

			}

			

			System::load_app_fun("content");

						

			$table = $shopinfo['codes_table'];

			$this->db->Autocommit_start();
			// 查询出所有的购买记录
			$lists = $this->db->GetList("SELECT uid,SUM(moneycount) AS money FROM `@#_member_go_record` WHERE `shopid` = '$gid' GROUP BY `uid`");
			if(!empty($lists)){
				foreach ($lists as $k => $v) {
					$money = $v['money'];
					$uid = $v['uid'];
					$time = time();
					$pay_type = '商品'.$shopinfo['id'].'-'.$shopinfo['qishu'].'期开奖失败退'.$money.'元';
					$this->db->Query("UPDATE `@#_member` SET `money` = `money`+ $money WHERE `uid` = $uid");
					// $this->db->Query("INSERT INTO `@#_member_addmoney_record` (`money`,`uid`,`pay_type`,`status`,`time`) VALUES ('$money', '$uid','$pay_type','已付款','$time')");
					$this->db->Query("INSERT INTO  `@#_member_account` SET `uid` = $uid, `type`=1, `pay`='账户', `content`='$pay_type', `money`='$money',`time`=$time");
				}
			}

			$q1 = $this->db->Query("DELETE FROM `@#_member_go_record` WHERE `shopid` = '$gid'");

			$q2 = $this->db->Query("DELETE FROM `@#_{$table}` WHERE `s_id` = '$gid'");			

			$zongrenshu = ceil($new_money/$new_one_m);		

			$q3 = content_get_go_codes($zongrenshu,3000,$gid);

			$q4 = $this->db->Query("UPDATE `@#_shoplist` SET 

			`canyurenshu` = '0',

			`zongrenshu` = '$zongrenshu', 

			`money` = '$new_money', 

			`yunjiage` = '$new_one_m', 

			`shenyurenshu` = `zongrenshu`

			where `id` = '$gid'");

			if($q1 && $q2 && $q3 && $q4){

					$this->db->Autocommit_commit();

					_message("更新成功!");

			}else{

				$this->db->Autocommit_rollback();

				_message("更新失败!");				

			}

		}

				

		include $this->tpl(ROUTE_M,'shop.set_money');

	}


	//晒单商品列表	

	public function shaidan_goods_list(){

		
	    $cateid=$this->segment(4);

	    $arr = $this->db->GetList("SELECT * FROM `@#_category` WHERE `model`='1'");

		$list_where = '';
		

		if(isset($_POST['sososubmit'])){	
			$list_where = '';		

			$posttime1 = !empty($_POST['posttime1']) ? strtotime($_POST['posttime1']) : NULL;

			$posttime2 = !empty($_POST['posttime2']) ? strtotime($_POST['posttime2']) : NULL;			

			$sotype = $_POST['sotype'];

			$sosotext = $_POST['sosotext'];	

			if($posttime1 && $posttime2){

				if($posttime2 < $posttime1)_message("结束时间不能小于开始时间");

				$list_where = "`time` > '$posttime1' AND `time` < '$posttime2' AND".$list_where;

			}

			if($posttime1 && empty($posttime2)){				

				$list_where = "`time` > '$posttime1' AND".$list_where;

			}

			if($posttime2 && empty($posttime1)){				

				$list_where = "`time` < '$posttime2' AND".$list_where;

			}

			if(!empty($sosotext)){			

				if($sotype == 'cateid'){

					$sosotext = intval($sosotext);

					if($list_where)

						$list_where .= "AND `cateid` = '$sosotext'";

					else

						$list_where = "`cateid` = '$sosotext'";

				}

				if($sotype == 'brandid'){

					$sosotext = intval($sosotext);

					if($list_where)

						$list_where .= "AND `brandid` = '$sosotext'";

					else

						$list_where = "`brandid` = '$sosotext'";

				}

				

				if($sotype == 'brandname'){

					$sosotext = htmlspecialchars($sosotext);

					$info = $this->db->GetOne("SELECT * FROM `@#_brand` where `name` LIKE '%$sosotext%' LIMIT 1");

					

					if($list_where && $info)

						$list_where .= "AND `brandid` = '$info[id]'";

					elseif ($info)

						$list_where = "`brandid` = '$info[id]'";

					else

						$list_where = "1";

				}

				

				

				if($sotype == 'catename'){

					$sosotext = htmlspecialchars($sosotext);

					$info = $this->db->GetOne("SELECT * FROM `@#_category` where `name` LIKE '%$sosotext%' LIMIT 1");

					

					if($list_where && $info)

						$list_where .= "AND `cateid` = '$info[cateid]'";

					elseif ($info)

						$list_where = "`cateid` = '$info[cateid]'";

					else

						$list_where = "1";

				}

				if($sotype == 'title'){

					$sosotext = htmlspecialchars($sosotext);

					$list_where = "`title` like '%$sosotext%'";

				}

				if($sotype == 'id'){

					$sosotext = intval($sosotext);

					$list_where = "`id` = '$sosotext'";

				}

				$list_where .= "and `q_uid` is null  order by `id` DESC";

			}else{

				if(!$list_where) $list_where='1';					

			}		
			$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_shoplist` WHERE $list_where"); 
			$shoplist=$this->db->GetList("SELECT * FROM `@#_shoplist` WHERE $list_where");
		}else{
			$num=20;

			$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_shoplist` WHERE $list_where"); 

			$page=System::load_sys_class('page');

			if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

			$page->config($total,$num,$pagenum,"0");

			$shoplist=$this->db->GetPage("SELECT * FROM `@#_shoplist` WHERE $list_where ",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));

		}	
		

		include $this->tpl(ROUTE_M,'shop.shaidan_lists');

	}

	public function goods_shaidan_list(){	

	    $cateid=$this->segment(4);

	    $arr = $this->db->GetList("SELECT * FROM `@#_category` WHERE `model`='1'");

		
		$num=20;

		$total=$this->db->GetCount("SELECT COUNT(*) FROM `@#_shoplist` WHERE `cateid` = '$cateid' AND `q_uid` is NULL "); 

		$page=System::load_sys_class('page');

		if(isset($_GET['p'])){$pagenum=$_GET['p'];}else{$pagenum=1;}	

		$page->config($total,$num,$pagenum,"0");

		$shoplist=$this->db->GetPage("SELECT * FROM `@#_shoplist` WHERE `cateid` = '$cateid' AND `q_uid` is NULL",array("num"=>$num,"page"=>$pagenum,"type"=>1,"cache"=>0));


		

		include $this->tpl(ROUTE_M,'shop.shaidan_lists');

	}

	public function shaidan_list(){
		$sid = $this->segment(4);
		$type = $_GET['type'];
		if($type == 1){
			$data = $this->db->GetOne("SELECT m.* FROM `@#_shoplist` AS m LEFT JOIN `@#_member` AS n ON m.q_uid = n.uid WHERE n.auto_user = '1' AND m.q_uid is not NULL AND `id` = '$sid'");
			if($_POST && $data){

				if($_POST['title']==null)_messagemobile("标题不能为空");
				if($_POST['content']==null)_messagemobile("内容不能为空");
				if(empty($_POST['fileurl_tmp'])){
					_messagemobile("图片不能为空");
				}
				System::load_sys_class('upload','sys','no');
				$img=explode(';', $_POST['fileurl_tmp']);
				$num=count($img);
				$pic="";
				for($i=0;$i<$num;$i++){
					$img[$i] = str_replace('http://', '', $img[$i]);
					$img[$i] = str_replace($_SERVER['HTTP_HOST'], '', $img[$i]);
					$img[$i] = str_replace('/statics/uploads/', '', $img[$i]);
					$pic.=trim($img[$i]).";";
				}


				foreach ($img as $key => $val) {
					$dz = $val;
					$x = file_get_contents("http://".$_SERVER['HTTP_HOST'].'/sc.php?object='.$dz.'&img=./statics/uploads/'.$dz);
					if($x == 'ok'){
						if(file_exists("./statics/uploads/".$dz)){
					        unlink("./statics/uploads/".$dz);
					    }
					}
				}

				$src=trim($img[0]);
				$size=getimagesize(G_UPLOAD_PATH."/".$src);
				$width=220;
				$height=$size[1]*($width/$size[0]);
				//$thumbs=tubimg($src,$width,$height);
				$thumbs=$src;
				$sd_userid=$data['uid'];
				$sd_shopid=intval($sid);
				$sd_title=safe_replace($_POST['title']);
				$sd_thumbs=$src;
				$sd_content=safe_replace($_POST['content']);
				$sd_photolist=$pic;
				$sd_time=time();
				$sd_ip = _get_ip();	
				$qishu= $this->db->GetOne("select `qishu`, `id`, `q_end_time` from `@#_shoplist` where `id`='$sid'");
				$qs = $qishu['qishu'];
				$ids = $qishu['id'];
				$q_end_time = intval($qishu['q_end_time']);
				$this->db->Query("INSERT INTO `@#_shaidan`(`sd_userid`,`sd_shopid`,`sd_qishu`,`sd_ip`,`sd_title`,`sd_thumbs`,`sd_content`,`sd_photolist`,`sd_time`,`q_end_time`)VALUES ('$sd_userid','$sd_shopid','$qs','$sd_ip','$sd_title','$sd_thumbs','$sd_content','$sd_photolist','$sd_time','$q_end_time')");
				header("Location:".WEB_PATH."/mobile/home/singlelist");
			}
		}else{
			$data = $this->db->GetList("SELECT m.* FROM `@#_shoplist` AS m LEFT JOIN `@#_member` AS n ON m.q_uid = n.uid WHERE n.auto_user = '1' AND m.q_uid is not NULL AND `sid` = '$sid' order by `id` desc LIMIT 100");
		}
		include $this->tpl(ROUTE_M,'shop.shaidan_list');
	}

	public function file_upload(){
		ini_set('display_errors', 0);
		// error_reporting(E_ALL);
		include dirname(__FILE__).DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."UploadHandler.php";
		$upload_handler = new UploadHandler();
	}

	public function xxxx(){
		// $data = $this->db->GetList("select `sid` from `@#_shoplist` where `time` > '1525042800'");
		// foreach ($data as $key => $val) {
		// 	$arr[] = $val['sid'];
		// }
		// $arr = array_unique($arr);
		// $data2 = $this->db->GetList("select `sid` from `@#_shoplist` where `time` > '1525042800' and `q_uid` is null");
		// foreach ($data2 as $key => $val) {
		// 	$brr[] = $val['sid'];
		// }
		// $brr[] = '2510269';
		// $brr[] = '2510272';
		// $brr[] = '2510265';
		// $brr[] = '2510270';
		// $brr = array_unique($brr);
		// $brr = implode(',', $brr);
		// $data3 = $this->db->GetList("select `sid` from `@#_shoplist` where `time` > '1525042800' and `sid` not in ($brr)");
		// foreach ($data3 as $key => $val) {
		// 	$crr[] = $val['sid'];
		// }
		// $crr = array_unique($crr);
		$aaa = "2631316";
		$bbb = explode(',', $aaa);
		foreach ($bbb as $key => $val) {
		
			$ccc = $this->db->GetOne("select * from `@#_shoplist` where `id` = '$val'");
			
			$buynum2 = intval($ccc['buynum2']);	

			$buynum = intval($ccc['buynum']);	

			$cateid = intval($ccc['cateid']);

			$brandid = intval($ccc['brand']);

			$title = $ccc['title'];

			$title_color = $ccc['title_style_color'];

			$title_bold = $ccc['title_style_bold'];

			$title2 = $ccc['title2'];

			$keywords = $ccc['keywords'];

			$description = $ccc['description'];

			$content = $ccc['content'];

			$money = intval($ccc['money']);

			$yunjiage = intval($ccc['yunjiage']);

			$thumb = $ccc['thumb'];		

			$maxqishu = intval($ccc['maxqishu']);			

			$canyurenshu = 0;		

			$goods_key_pos = $ccc['pos'];

			$goods_key_renqi = $ccc['renqi'];

			$shop_money = $ccc['str1'];

			
			$picarr = $ccc['picarr'];
			


			$xsjx_time = 0;
			$title_style = $ccc['title_style'];
			$zongrenshu = $ccc['zongrenshu'];
			$canyurenshu = 0;
			$shenyurenshu = $ccc['zongrenshu'];

			$time=time();	//商品添加时间		

			$this->db->Autocommit_start();

					

			$query_1 = $this->db->Query("INSERT INTO `@#_shoplist` (`cateid`, `brandid`, `title`, `title_style`, `title2`, `keywords`, `description`, `money`, `yunjiage`, `zongrenshu`, `canyurenshu`,`shenyurenshu`, `qishu`,`maxqishu`,`thumb`, `picarr`, `content`,`xsjx_time`,`renqi`,`pos`, `time`,`buynum`,`buynum2`,`str1`) VALUES ('$cateid', '$brandid', '$title', '$title_style', '$title2', '$keywords', '$description', '$money', '$yunjiage', '$zongrenshu', '$canyurenshu','$shenyurenshu', '1','$maxqishu', '$thumb', '$picarr', '$content','$xsjx_time','$goods_key_renqi', '$goods_key_pos','$time','$buynum','$buynum2','$shop_money')");			

			$shopid = $this->db->insert_id();

			System::load_app_fun("content");		

			$query_table = content_get_codes_table();

			if(!$query_table){

				$this->db->Autocommit_rollback();

				_message("参与码仓库不正确!");

			}

			$query_2 = content_get_go_codes($zongrenshu,3000,$shopid);

			$query_3 = $this->db->Query("UPDATE `@#_shoplist` SET `codes_table` = '$query_table',`sid` = '$shopid',`def_renshu` = '$canyurenshu' where `id` = '$shopid'");

					

			if($query_1 && $query_2 && $query_3){

				$this->db->Autocommit_commit();				

				echo $shopid;

			}else{		

			

				$this->db->Autocommit_rollback();

				_message("商品添加失败!");

			}
		}	

	}

	

}//


?>