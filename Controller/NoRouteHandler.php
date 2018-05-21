<?php

namespace Atharva\NoRoute\Controller;

use Magento\Framework\App\RequestInterface as Request;
use Magento\Framework\Registry;


class NoRouteHandler implements \Magento\Framework\App\Router\NoRouteHandlerInterface
{  
	
	protected $responseFactory;
	protected $url;
	/* protected $_scopeConfig; */
	protected $category;
	protected $product;

	public function __construct(
		\Magento\Framework\App\ResponseFactory $responseFactory,
		\Magento\Framework\UrlInterface $url,
		\Magento\Catalog\Model\CategoryFactory $categoryFactory,
		\Magento\Catalog\Model\ProductFactory $product
		/* \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig */
	) {
		$this->responseFactory = $responseFactory;
		$this->url = $url;
		$this->category = $categoryFactory;
		$this->product = $product;
		/* $this->_scopeConfig = $scopeConfig; */
	}
	
    public function process(Request $request)
    {
        $pathInfo = $request->getPathInfo();
        $parts = explode('/', trim($pathInfo, '/'));
		
        $moduleName = isset($parts[0]) ? $parts[0] : '';
        $actionPath = isset($parts[1]) ? $parts[1] : '';
        $actionName = isset($parts[2]) ? $parts[2] : '';
		/* Catalog Level */
		if($moduleName == 'catalog') {
			$customRedirectionUrl = $this->url->getUrl(''); //Get url of cms page
			$this->responseFactory->create()->setRedirect($customRedirectionUrl)->sendResponse();
			exit;
        } else {
			$checkCat = [];
			$checkCat[0] = "";
			$checkCat[1] = "";
			$checkCat = explode('/', trim($pathInfo, '/'));
			
			if (strpos($checkCat[0], '.') !== false) {
				$checkCatR[0] = "";
				$checkCatR[1] = "";
				$checkCatR = explode('.', trim($pathInfo, '/'));
				$checkCat[0] = $checkCatR[0];
			}
			
			$categories = $this->category->create()
						->getCollection()
						->addAttributeToFilter('url_key',$checkCat[0])
						->addAttributeToSelect(['entity_id']);
			
			
			$products = $this->product->create()
						->getCollection()
						->addAttributeToFilter('url_key',$checkCat[0])
						->addAttributeToSelect(['entity_id']);
			
			/* category level */
			if(!empty($categories->getData())) {
				$customRedirectionUrl = $this->url->getUrl(''); //Get url of cms page
				$this->responseFactory->create()->setRedirect($customRedirectionUrl)->sendResponse();
				exit;
			} 
			/* product level */
			elseif(!empty($products->getData())) {
				$customRedirectionUrl = $this->url->getUrl(''); //Get url of cms page
				$this->responseFactory->create()->setRedirect($customRedirectionUrl)->sendResponse();
				exit;
			} 
			/* other links */
			else {
				$customRedirectionUrlHome = $this->url->getUrl(); //Get url of cms page
				$this->responseFactory->create()->setRedirect($customRedirectionUrlHome)->sendResponse();
				exit;
			}
		}
		
    }
}
