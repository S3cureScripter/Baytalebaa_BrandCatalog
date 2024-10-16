<?php
declare(strict_types=1);
namespace Baytalebaa\Shops\Controller;


use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RouterInterface;

class Router implements RouterInterface
{
    protected $actionFactory;
    protected $shopsFactory;
    protected $logger;

    public function __construct(
        ActionFactory $actionFactory, 
        \Baytalebaa\Shops\Model\ShopsFactory $shopsFactory, 
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->actionFactory = $actionFactory;
        $this->shopsFactory = $shopsFactory;
        $this->logger = $logger;
    }

    public function match(RequestInterface $request)
    {
        try {
            $this->logger->debug('Custom router is invoked');
            
            // Get the current module, controller, and action
            $moduleName = $request->getModuleName();
            $controllerName = $request->getControllerName();
            $actionName = $request->getActionName();
            ////echo('<h1>{{--------------------------------------START-------------------------------------}}</h1>');

            // //echo("<br/>=====================================<br/>");
            
            // //echo('<h1>$moduleName:<h2>{{'. $moduleName."}}</h2>");
            // //echo('<h1>controllerName:<h2>{{'.$controllerName."}}</h2>");
            // //echo('<h1>actionName:</h1><h2>{{'.$actionName."}}</h2>");
            // //echo('<h1>($moduleName === shops && $controllerName === index && $actionName === index&& $request->getParam(shop):</h1><h2>{{'.($moduleName === 'shops' && $controllerName === 'index' && $actionName === 'index'&& $request->getParam('shop'))."}}</h2>");
            // //echo('<h1>($moduleName === shops && $controllerName === index && $actionName === view:</h1><h2>{{'.($moduleName === 'shops' && $controllerName === 'index' && $actionName === 'view')."}}</h2>");
            // //echo('<h1>($moduleName === shops && $controllerName === index && $actionName === view&& $request->getParam(shop):</h1><h2>{{'.($moduleName === 'shops' && $controllerName === 'index' && $actionName === 'view'&& $request->getParam('shop'))."}}</h2>");
            // //echo('<h1>($moduleName === shops && $controllerName === index && $actionName === view&& $request->getParam(catalog):</h1><h2>{{'.($moduleName === 'shops' && $controllerName === 'index' && $actionName === 'view'&& $request->getParam('catalog'))."}}</h2>");
            // //echo('<h1>($moduleName === shops && $controllerName === index && $actionName === view&& $request->getParam(shop)&& $request->getParam(catalog):</h1><h2>{{'.($moduleName === 'shops' && $controllerName === 'index' && $actionName === 'view'&& $request->getParam('shop')&& $request->getParam('catalog'))."}}</h2>");

            // //echo("<br/>=====================================<br/>");
  
            // Avoid infinite loop by checking the current route
            if ($moduleName === 'shops' && $controllerName === 'index' && $actionName === 'view'&& $request->getParam('catalog')) {
                return null;
            }
            if ($moduleName === 'shops' && $controllerName === 'index' && $actionName === 'view') {
                return null;
            }
            if ($moduleName === 'shops' && $controllerName === 'index' && $actionName === 'index'&& $request->getParam('shop')) {
                return null;
            }
            // Get the requested URL path and extract the shop title
            $identifier = trim($request->getPathInfo(), '/');
            $identifierParts = explode('/', $identifier);
            //echo('<h1>$identifierParts:</h1> <h2>{{'.implode(" ",$identifierParts)."}}</h2>");

            if (count($identifierParts) > 1 && $identifierParts[0] === 'shops') {
            //     $shopTitle = $identifierParts[1];
    
            //    ////echo('Looking for shops_id: {{' . $shopTitle."}}");
                
            //     // // Load the shop by title
            //     $shop = $this->shopsFactory->create()->load($shopTitle, 'title');
            //     ////echo('<h1>Load shop:<h2>{{'.$shop->getTitle()."}}</h2>");
            //     if ($shop->getId()) {
            //         $request->setModuleName('shops')
            //                 ->setControllerName('index')
            //                 ->setActionName('index')
            //                 ->setParam('shop', $shop->getId());
    
            //         return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
            //     }
                if ($identifierParts[0] === 'shops') {
                    $shopSlug = $identifierParts[1] ?? null;
                    $catalogSlug = $identifierParts[2] ?? null;
                    $subcatalogSlug = $identifierParts[3] ?? null;

                    if ($shopSlug) {
                        $shop = $this->shopsFactory->create()->load($shopSlug, 'url_slug');

                        if ($shop->getId()) {
                            if ($catalogSlug) {
                                if ($subcatalogSlug) {
                                    // Handle /shops/<shop_slug>/<catalog_slug>/<subcatalog_slug>
                                    // $request->setModuleName('shops')
                                    //     ->setControllerName('index')
                                    //     ->setActionName('subcatalog')
                                    //     ->setParam('shop', $shop->getId())
                                    //     ->setParam('catalog', $catalogSlug)
                                    //     ->setParam('id', $subcatalogSlug);
                                } else {
                                    // Handle /shops/<shop_slug>/<catalog_slug>
                                    $request->setModuleName('shops')
                                        ->setControllerName('index')
                                        ->setActionName('view')
                                        // ->setParam('shop', $shop->getId())
                                        ->setParam('catalog', $catalogSlug);
                                }
                            } else {
                                // Handle /shops/<shop_slug>
                                $request->setModuleName('shops')
                                    ->setControllerName('index')
                                    ->setActionName('index')
                                    ->setParam('shop', $shop->getId());
                            }

                            return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $this->logger->error('Error in custom router: ' . $e->getMessage());
        }
    
        return null;
    }
    
    
}