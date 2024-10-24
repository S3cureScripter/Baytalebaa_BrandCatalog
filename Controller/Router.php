<?php
declare(strict_types=1);
namespace Baytalebaa\Shops\Controller;

use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RouterInterface;
use Baytalebaa\Shops\Model\ShopsFactory as RouterFactory;
use Baytalebaa\Shops\Model\CatalogsFactory as CatalogFactory;
use Baytalebaa\Shops\Model\SubcatalogsFactory as SubcatalogFactory;
use Magento\Store\Model\StoreManagerInterface;

class Router implements RouterInterface
{
    protected $actionFactory;
    protected $routerFactory;
    protected $catalogFactory;
    protected $subcatalogFactory;
    protected $logger;
    protected $storeManager;

    public function __construct(
        ActionFactory $actionFactory,
        RouterFactory $routerFactory,
        CatalogFactory $catalogFactory,
        SubcatalogFactory $subcatalogFactory,
        \Psr\Log\LoggerInterface $logger,
        StoreManagerInterface $storeManager
    ) {
        $this->actionFactory = $actionFactory;
        $this->routerFactory = $routerFactory;
        $this->catalogFactory = $catalogFactory;
        $this->subcatalogFactory = $subcatalogFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    public function match(RequestInterface $request)
    {
        try {
            $identifier = trim($request->getPathInfo(), '/');
            $identifierParts = explode('/', $identifier);

            if (!$this->shouldProcessRequest($request, $identifierParts)) {
                // echo "try ->match -> null";
                return null;
            }

            $shopSlug = $identifierParts[1] ?? null;
            $catalogSlug = $identifierParts[2] ?? null;
            $subcatalogSlug = $identifierParts[3] ?? null;
            $storeId = $this->storeManager->getStore()->getId();
            $debug_response = [
                'debug_response - match' => [
                    'identifierParts' => $identifierParts,
                    'shopSlug' => $catalogSlug,
                    'catalogSlug' => $catalogSlug,
                    'subcatalogSlug' => $subcatalogSlug,
                    'storeId' => $storeId
                ]
            ];
            // echo '<pre>';
            // echo json_encode($debug_response, JSON_PRETTY_PRINT);
            // echo '<hr>';
            // echo '</pre>';

            if ($shopSlug) {
                // echo "match -> shopSlug";
                // Load shop by URL slug and store ID
                $shop = $this->routerFactory->create()->getCollection()
                    ->addFieldToFilter('url_slug', $shopSlug)
                    ->addFieldToFilter('store_id', $storeId)
                    ->getFirstItem();
                    if (!$shop->getId()) {
                        // echo "match -> shopSlug -> return null";
                        return null;
                    }
                    $debug_response = [
                        'debug_response - match - shopSlug' => [
                            'identifierParts' => $identifierParts,
                            'shopSlug' => $shopSlug,
                            '$shop->getId()' => $shop->getId(),
                            'storeId' => $storeId
                        ]
                    ];
                    // echo '<pre>';
                    // echo json_encode($debug_response, JSON_PRETTY_PRINT);
                    // echo '<hr>';
                    // echo '</pre>';

                if ($subcatalogSlug) {
                    // echo "match -> shopSlug ->subcatalogSlug";
                    // Validate catalog exists for this shop
                    $catalog = $this->catalogFactory->create()->getCollection()
                        ->addFieldToFilter('url_slug', $catalogSlug)
                        ->addFieldToFilter('shop', $shop->getId())
                        ->addFieldToFilter('store_id', $storeId)
                        ->getFirstItem();
                        if (!$catalog->getId()) {
                            return null;
                        }
                        $debug_response = [
                            'debug_response - match - shopSlug - subcatalogSlug/catalog' => [
                                'identifierParts' => $identifierParts,
                                'catalogSlug' => $catalogSlug,
                                '$shop->getId()' => $shop->getId(),
                                '$catalog' => $catalog->debug()
                            ]
                        ];
                        // echo '<pre>';
                        // echo json_encode($debug_response, JSON_PRETTY_PRINT);
                        // echo '<hr>';
                        // echo '</pre>';

                    // Validate subcatalog exists for this catalog
                    $subcatalog = $this->subcatalogFactory->create()->getCollection()
                        ->addFieldToFilter('url_slug', $subcatalogSlug)
                        ->addFieldToFilter('catalog_id', $catalog->getId())
                        ->addFieldToFilter('store_id', $storeId)
                        ->getFirstItem();
                    $debug_response = [
                        'debug_response - match - shopSlug - subcatalogSlug/subcatalog' => [
                            'identifierParts' => $identifierParts,
                            'subcatalogSlug' => $subcatalogSlug,
                            '$catalog->getId()' => $catalog->getId(),
                            '$subcatalog' => $subcatalog->debug()
                        ]
                    ];
                    // echo '<pre>';
                    // echo json_encode($debug_response, JSON_PRETTY_PRINT);
                    // echo '<hr>';
                    // echo '</pre>';
                    if (!$subcatalog->getId()) {
                        return null;
                    }

                    $this->setRequestPath($request, 'subcatalog', [
                        'shop' => $shop->getId(),
                        'catalog' => $catalog->getId(),
                        'subcatalog' => $subcatalog->getId()
                    ]);
                } elseif ($catalogSlug) {
                    // echo "match -> shopSlug ->catalogSlug";

                    // Validate catalog exists for this shop

                    $catalog = $this->catalogFactory->create()->getCollection()
                        ->addFieldToFilter('url_slug', $catalogSlug)
                        // ->addFieldToFilter('shop', $shop->getId())
                        ->addFieldToFilter('store_id', $storeId)
                        ->getFirstItem();
                    $debug_response = [
                        'debug_response - match - catalogSlug' => [
                            'catalogSlug' => $catalogSlug,
                            'url_slug' => $catalogSlug,
                            'store_id' => $shop->getId(),
                            'shop' => $storeId,
                            'catalog' => $catalog
                        ]
                    ];
                    // echo '<pre>';
                    // echo json_encode($debug_response, JSON_PRETTY_PRINT);
                    // echo '<hr>';
                    // echo '</pre>';

                    if (!$catalog->getId()) {
                        // echo "match -> shopSlug ->catalogSlug -> null";
                        return null;
                    }

                    $this->setRequestPath($request, 'catalog', [
                        'shop' => $shop->getId(),
                        'catalog' => $catalog->getId()
                    ]);
                } else {
                    // echo "match -> shopSlug ->else";
                    $debug_response = [
                        'debug_response - match - shopSlug - else' => [
                            'identifierParts' => $identifierParts,
                            'request' => $request,
                            '$shop->getId()' => $shop->getId(),
                            'storeId' => $storeId
                        ]
                    ];
                    // echo '<pre>';
                    // echo json_encode($debug_response, JSON_PRETTY_PRINT);
                    // echo '<hr>';
                    // echo '</pre>';
                    $this->setRequestPath($request, 'index', [
                        'shop' => $shop->getId()
                    ]);
                }

                return $this->actionFactory->create(Forward::class);
            }
        } catch (\Exception $e) {
            $this->logger->error('Error in custom router: ' . $e->getMessage());
        }

        return null;
    }

    private function shouldProcessRequest(RequestInterface $request, array $identifierParts): bool
    {
        $moduleName = $request->getModuleName();
        $controllerName = $request->getControllerName();
        $actionName = $request->getActionName();

        $debug_response = [
            'debug_response - shouldProcessRequest' => [
                'moduleName' => $moduleName,
                'controllerName' => $controllerName,
                'actionName' => $actionName,
                '($moduleName === \'shops\' && $controllerName.....' => ($moduleName === 'shops' && $controllerName === 'index' && in_array($actionName, ['index', 'catalogs', 'subcatalogs']) && ($request->getParam('shop') || $request->getParam('catalog') || $request->getParam('subcatalog'))),                
                '(empty($identifierParts[0]) || $identifierParts[0] !== \'shops\')' => (empty($identifierParts[0]) || $identifierParts[0] !== 'shops')
            ],
            'parameters' => [
                'shop' => $request->getParam('shop'),
                'catalog' => $request->getParam('catalog'),
                'subcatalog' => $request->getParam('subcatalog')
            ]
        ];
        // echo '<pre>';
        // echo json_encode($debug_response, JSON_PRETTY_PRINT);
        // echo '<hr>';
        // echo '</pre>';
        if (empty($identifierParts[0]) || $identifierParts[0] !== 'shops') {
            return false;
        }


        if ($moduleName === 'shops' && $controllerName === 'index' && 
            in_array($actionName, ['index', 'catalogs', 'subcatalogs']) && 
            ($request->getParam('shop') || $request->getParam('catalog') || $request->getParam('subcatalog'))) {
            $this->logger->debug('Router: Request already processed', $debug_response);
            return false;
        }

        // $this->logger->debug('Router: Processing new request', $debug_response);

        return true;
    }

    private function setRequestPath(RequestInterface $request, string $action, array $params): void
    {
        $debug_response = [
            'debug_response - setRequestPath' => [
                'moduleName' => "shops",
                'controllerName' => "index",
                'actionName' => $action
            ],
            'parameters' => $params
        ];
        // echo '<pre>';
        // echo json_encode($debug_response, JSON_PRETTY_PRINT);
        // echo '<hr>';
        // echo '</pre>';

        $request->setModuleName('shops')
            ->setControllerName('index')
            ->setActionName($action)
            ->setPathInfo('');

        foreach ($params as $key => $value) {
            $request->setParam($key, $value);
        }
    }
}