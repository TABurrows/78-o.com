<?php

$requestValidationSchema = [
        'limit'=> [
                'filter'=> FILTER_VALIDATE_INT,
                'options'=> [
                        'default'=>6,
                        'min_range'=> 1,
                        'max_range'=> 20]
        ],
        'select'=> [
                'filter'=> FILTER_VALIDATE_REGEXP,
                'options' => [
                        'default'=>'id',
                        'regexp' => '/^[a-z0-9\_\|]*$/i']                                    
        ],
        'where'=> [
                'filter'=> FILTER_VALIDATE_REGEXP,
                'options' => ['regexp' => '/^[a-z0-9\_\|]*$/i']                                     
        ],
        'equals' => [
                'filter'=> FILTER_VALIDATE_REGEXP,
                'options' => ['regexp' => '/^[0-9]?\:||\.{1}$/']                                    
        ],
        'average' => [
                'filter'=> FILTER_VALIDATE_REGEXP,
                'options' => ['regexp' => '/^[a-z0-9\_]*$/i']                                    
        ],
        'greater' => [
                'filter'=> FILTER_VALIDATE_REGEXP,
                'options' => ['regexp' => '/^[0-9]?\:||\.{1}$/']                                    
        ],
        'less' => [
                'filter'=> FILTER_VALIDATE_REGEXP,
                'options' => ['regexp' => '/^[0-9]?\:||\.{1}$/']                                    
        ],
        'by'=> [
                'filter'=> FILTER_VALIDATE_REGEXP,
                'options' => ['regexp' => '/^[a-z0-9\_\|]*$/i']                                     
        ],
        'order'=> [
                'filter'=> FILTER_VALIDATE_REGEXP,
                'options'=> ['regexp'=> '/ASC|DESC/i']
        ]
 ];