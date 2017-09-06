<?php namespace Anomaly\ProductDiscountFilterExtension\Command;

use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\DiscountsModule\Discount\Contract\DiscountInterface;
use Anomaly\DiscountsModule\Filter\Contract\FilterInterface;
use Anomaly\DiscountsModule\Filter\Extension\FilterExtension;
use Anomaly\ProductsModule\Product\Contract\ProductInterface;
use Anomaly\ProductsModule\Product\Contract\ProductRepositoryInterface;
use Illuminate\Translation\Translator;

/**
 * Class GetColumnValue
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 * @package       Anomaly\ProductDiscountFilterExtension\Command
 */
class GetColumnValue
{

    /**
     * The discount interface.
     *
     * @var DiscountInterface
     */
    protected $discount;

    /**
     * The filter interface.
     *
     * @var FilterInterface
     */
    protected $filter;

    /**
     * The filter extension.
     *
     * @var FilterExtension
     */
    protected $extension;

    /**
     * Create a new GetColumnValue instance.
     *
     * @param FilterExtension   $extension
     * @param DiscountInterface $discount
     * @param FilterInterface   $filter
     */
    public function __construct(
        FilterExtension $extension,
        DiscountInterface $discount,
        FilterInterface $filter = null
    ) {
        $this->discount  = $discount;
        $this->filter    = $filter;
        $this->extension = $extension;
    }

    /**
     * Handle the command.
     *
     * @return string
     */
    public function handle(
        Translator $translator,
        ProductRepositoryInterface $products,
        ConfigurationRepositoryInterface $configuration
    ) {
        $operator = $configuration->presenter(
            'anomaly.extension.product_discount_filter::operator',
            $this->filter->getId()
        )->value;

        /* @var ProductInterface $value */
        if ($value = $products->find(
            $configuration->value('anomaly.extension.product_discount_filter::value', $this->filter->getId())
        )
        ) {
            $value = $value->getName();
        }

        return $translator->trans(
            'anomaly.extension.product_discount_filter::message.filter',
            compact('operator', 'value')
        );
    }
}
