<?php

class ABSoft_StoreListing_Adminhtml_StorelocationController extends Mage_Adminhtml_Controller_Action
{
		protected function _isAllowed()
		{
		//return Mage::getSingleton('admin/session')->isAllowed('storelisting/storelocation');
			return true;
		}

		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("storelisting/storelocation")->_addBreadcrumb(Mage::helper("adminhtml")->__("Storelocation  Manager"),Mage::helper("adminhtml")->__("Storelocation Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("StoreListing"));
			    $this->_title($this->__("Manager Storelocation"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{
			    $this->_title($this->__("StoreListing"));
				$this->_title($this->__("Storelocation"));
			    $this->_title($this->__("Edit Item"));

				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("storelisting/storelocation")->load($id);
				if ($model->getId() || $id == 0) {
					Mage::register("storelocation_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("storelisting/storelocation");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Storelocation Manager"), Mage::helper("adminhtml")->__("Storelocation Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Storelocation Description"), Mage::helper("adminhtml")->__("Storelocation Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("storelisting/adminhtml_storelocation_edit"))
                        ->_addLeft($this->getLayout()->createBlock("storelisting/adminhtml_storelocation_edit_tabs"));
					$this->renderLayout();
				}
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("storelisting")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

            $this->_forward('edit');

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();

				if ($post_data) {

					try {

                        if(isset($_FILES['img']['name']) and (file_exists($_FILES['img']['tmp_name']))) {
                            try {
                                $uploader = new Varien_File_Uploader('img');
                                $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); // or pdf or anything


                                $uploader->setAllowRenameFiles(false);

                                // setAllowRenameFiles(true) -> move your file in a folder the magento way
                                // setAllowRenameFiles(true) -> move your file directly in the $path folder
                                $uploader->setFilesDispersion(false);

                                $path = Mage::getBaseDir('media') . DS."storelisting".DS ;
                                $name_img = time().$_FILES['img']['name'];
                                $uploader->save($path, $name_img);

                                $post_data['img'] = $name_img;
                            }catch(Exception $e) {

                            }
                        } else {
                            if (isset($post_data['img']['delete']) && $post_data['img']['delete'] == 1)
                            {
                                if ($post_data['img']['value'] != '')
                                    unlink();
                                    $this->removeFile($post_data['img']['value']);
                                    $post_data['img'] = '';
                            }
                            else
                            {
//                                unset($post_data['main_image']);
                            }
                        }

                        $model = Mage::getModel("storelisting/storelocation")
                            ->setData($post_data)
						    ->setId($this->getRequest()->getParam("id"))
						    ->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Storelocation was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setStorelocationData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setStorelocationData($this->getRequest()->getPost());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				$this->_redirect("*/*/");
		}


        public function removeFile($file)
        {
//            $_helper = Mage::helper('ram');
//            $file = $_helper->updateDirSepereator($file);
            $directory = Mage::getBaseDir('media') . DS .'storelisting'. DS .$file ;
            $io = new Varien_Io_File();
            $result = $io->rmdir($directory, true);
        }
		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("storelisting/storelocation");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}

		
		public function massRemoveAction()
		{
			try {
				$ids = $this->getRequest()->getPost('store_location_ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("storelisting/storelocation");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
		/**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'storelocation.csv';
			$grid       = $this->getLayout()->createBlock('storelisting/adminhtml_storelocation_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'storelocation.xml';
			$grid       = $this->getLayout()->createBlock('storelisting/adminhtml_storelocation_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
//
//<div class="container">
//    {{block type="filterproducts/latest_home_list" product_count="8" column_count="4" template="filterproducts/flex_grid.phtml" category_id="2" }}
//</div>
//<div style="background-color:#efefef">
//	<div class="container">
//		<div id="brands-slider-demo-17" class="owl-no-narrow flex-owl-slider">
//			<div  class="owl-carousel owl-theme">
//				<div class="item" style="padding-top:10px;"><img src="{{media url="wysiwyg/finnlash/brand/finnlash.png"}}" alt="" /></div>
//				<div class="item" style="padding-top:10px;"><img src="{{media url="wysiwyg/finnlash/brand/hohoemi.png"}}" alt="" /></div>
//				<div class="item" style="padding-top:10px;"><img src="{{media url="wysiwyg/finnlash/brand/lelux.png"}}" alt="" /></div>
//				<div class="item" style="padding-top:10px;"><img src="{{media url="wysiwyg/finnlash/brand/finnlash.png"}}" alt="" /></div>
//			</div>
//			<script type="text/javascript">
//    jQuery(function($){
//        $("#brands-slider-demo-17 .owl-carousel").owlCarousel({
//					lazyLoad: true,
//					itemsCustom: [ [0, 1], [320, 2], [992, 3], [1200, 4] ],
//					responsiveRefreshRate: 50,
//					slideSpeed: 200,
//					paginationSpeed: 500,
//					scrollPerPage: false,
//					stopOnHover: true,
//					rewindNav: true,
//					rewindSpeed: 600,
//					pagination: false,
//					navigation: false,
//					autoPlay: false
//				});
//			});
//			</script>
//		</div>
//	</div>
//</div>