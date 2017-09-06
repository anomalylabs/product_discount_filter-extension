<?php

return [
    'operator' => [
        'required' => true,
        'type'     => 'anomaly.field_type.select',
        'config'   => [
            'options' => [
                'equal_to' => 'anomaly.extension.product_discount_filter::configuration.operator.options.equal_to',
            ],
        ],
    ],
    'value'    => [
        'required' => true,
        'type'     => 'anomaly.field_type.relationship',
        'config'   => [
            'mode'    => 'lookup',
            'related' => 'Anomaly\ProductsModule\Product\ProductModel',
        ],
    ],
];
