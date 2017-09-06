<?php namespace Anomaly\ProductDiscountFilterExtension\Command;

use Anomaly\CartsModule\Item\Contract\ItemInterface;
use Anomaly\ProductDiscountFilterExtension\ProductDiscountFilterExtension;
use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\ProductsModule\Product\Contract\ProductInterface;
use Anomaly\ProductsModule\Product\Contract\ProductRepositoryInterface;
use Anomaly\ProductsModule\Configuration\Contract\ConfigurationInterface;

/**
 * Class ValidateDiscountFilter
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 * @package       Anomaly\ProductDiscountFilterExtension\Command
 */
class ValidateDiscountFilter
{

    /**
     * The extension instance.
     *
     * @var ProductDiscountFilterExtension
     */
    private $extension;

    /**
     * The target object.
     *
     * @var mixed
     */
    private $target;

    /**
     * Create a new ValidateDiscountFilter instance.
     *
     * @param ProductDiscountFilterExtension $extension
     * @param                                      $target
     */
    public function __construct(ProductDiscountFilterExtension $extension, $target)
    {
        $this->extension = $extension;
        $this->target    = $target;
    }

    /**
     * Handle the command.
     *
     * @param ProductRepositoryInterface $products
     * @param ConfigurationRepositoryInterface $configuration
     * @return string
     */
    public function handle(ProductRepositoryInterface $products, ConfigurationRepositoryInterface $configuration)
    {

        /**
         * We have to have an item since products
         * only exist on products via cart items.
         */
        if (!$this->target instanceof ItemInterface) {
            return false;
        }

        /**
         * We have to have a purchasable item
         * which should be a configuration.
         */
        if (!$purchasable = $this->target->getEntry()) {
            return false;
        }

        /**
         * Make sure we have the
         * interface we need,
         */
        if (!$purchasable instanceof ConfigurationInterface) {
            return false;
        }

        /* @var ProductInterface $value */
        if (!$value = $products->find(
            $configuration->value(
                'anomaly.extension.product_discount_filter::value',
                $this->extension->getFilter()->getId()
            )
        )
        ) {
            return false;
        }

        return $purchasable
            ->getProduct()
            ->getProducts()
            ->find($value);
    }
}
