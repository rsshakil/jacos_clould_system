<?php

use Illuminate\Database\Seeder;

class cmn_connectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $cmn_connect = array(
            [
                'byr_buyer_id'=>1,
                'byr_shop_id'=>0,
                'slr_seller_id'=>1,
                'slr_ware_house_id'=>0,
                'partner_code'=>'010690',
                'is_active'=>1,
                'optional'=>'{
                    "order": {
                        "fax": {
                            "number": "0364503611",
                            "exec": true
                        },
                        "download": "7"
                    },
                    "invoice": {
                        "closing_date": [
                            10,
                            20,
                            "last"
                        ]
                    },
                    "payment": {
                        "fax": {
                            "number": "0364503611",
                            "exec": false
                        }
                    }
                }

                ',
            ],[
                'byr_buyer_id'=>1,
                'byr_shop_id'=>0,
                'slr_seller_id'=>2,
                'slr_ware_house_id'=>0,
                'partner_code'=>'010540',
                'is_active'=>1,
                'optional'=>'{
                    "order": {
                        "fax": {
                            "number": "0364503611",
                            "exec": true
                        },
                        "download": "7"
                    },
                    "invoice": {
                        "closing_date": [
                            "10",
                            "20",
                            "last"
                        ]
                    },
                    "payment": {
                        "fax": {
                            "number": "0364503611",
                            "exec": false
                        }
                    }
                }

                ',
            ],[
                'byr_buyer_id'=>1,
                'byr_shop_id'=>0,
                'slr_seller_id'=>3,
                'slr_ware_house_id'=>0,
                'partner_code'=>'011980',
                'is_active'=>1,
                'optional'=>'{
                    "order": {
                        "fax": {
                            "number": "0364503611",
                            "exec": true
                        },
                        "download": "7"
                    },
                    "invoice": {
                        "closing_date": [
                            "10",
                            "20",
                            "last"
                        ]
                    },
                    "payment": {
                        "fax": {
                            "number": "0364503611",
                            "exec": false
                        }
                    }
                }

                ',
            ],[
                'byr_buyer_id'=>1,
                'byr_shop_id'=>0,
                'slr_seller_id'=>4,
                'slr_ware_house_id'=>0,
                'partner_code'=>'012060',
                'is_active'=>1,
                'optional'=>'{
                    "order": {
                        "fax": {
                            "number": "0364503611",
                            "exec": true
                        },
                        "download": "7"
                    },
                    "invoice": {
                        "closing_date": [
                            "10",
                            "20",
                            "last"
                        ]
                    },
                    "payment": {
                        "fax": {
                            "number": "0364503611",
                            "exec": false
                        }
                    }
                }

                ',
            ],
            [
                'byr_buyer_id'=>2,
                'byr_shop_id'=>0,
                'slr_seller_id'=>7,
                'slr_ware_house_id'=>0,
                'partner_code'=>'012060',
                'is_active'=>1,
                'optional'=>'{
                    "order": {
                        "fax": {
                            "number": "0364503611",
                            "exec": true
                        },
                        "download": "7"
                    },
                    "invoice": {
                        "closing_date": [
                            "10",
                            "20",
                            "last"
                        ]
                    },
                    "payment": {
                        "fax": {
                            "number": "0364503611",
                            "exec": false
                        }
                    }
                }

                ',
            ],
            [
                'byr_buyer_id'=>1,
                'byr_shop_id'=>0,
                'slr_seller_id'=>5,
                'slr_ware_house_id'=>0,
                'partner_code'=>'993477',
                'is_active'=>1,
                'optional'=>'{
                    "order": {
                        "fax": {
                            "number": "0364503611",
                            "exec": true
                        },
                        "download": "7"
                    },
                    "invoice": {
                        "closing_date": [
                            "10"
                        ]
                    },
                    "payment": {
                        "fax": {
                            "number": "0364503611",
                            "exec": false
                        }
                    }
                }',
            ],
            [
                'byr_buyer_id'=>1,
                'byr_shop_id'=>0,
                'slr_seller_id'=>5,
                'slr_ware_house_id'=>0,
                'partner_code'=>'993478',
                'is_active'=>1,
                'optional'=>'{
                    "order": {
                        "fax": {
                            "number": "0364503611",
                            "exec": false
                        },
                        "download": "7"
                    },
                    "invoice": {
                        "closing_date": [
                            "10"
                        ]
                    },
                    "payment": {
                        "fax": {
                            "number": "0364503611",
                            "exec": false
                        }
                    }
                }',
            ],
            [
                'byr_buyer_id'=>1,
                'byr_shop_id'=>0,
                'slr_seller_id'=>5,
                'slr_ware_house_id'=>0,
                'partner_code'=>'993479',
                'is_active'=>1,
                'optional'=>'{
                    "order": {
                        "fax": {
                            "number": "0364503611",
                            "exec": false
                        },
                        "download": "7"
                    },
                    "invoice": {
                        "closing_date": [
                            "10"
                        ]
                    },
                    "payment": {
                        "fax": {
                            "number": "0364503611",
                            "exec": false
                        }
                    }
                }',
            ],
            [
                'byr_buyer_id'=>1,
                'byr_shop_id'=>0,
                'slr_seller_id'=>6,
                'slr_ware_house_id'=>0,
                'partner_code'=>'000000',
                'is_active'=>1,
                'optional'=>'{
                    "order": {
                        "fax": {
                            "number": "0364503611",
                            "exec": false
                        },
                        "download": "7"
                    },
                    "invoice": {
                        "closing_date": [
                            "10"
                        ]
                    },
                    "payment": {
                        "fax": {
                            "number": "0364503611",
                            "exec": false
                        }
                    }
                }',
            ]

        );
        App\Models\CMN\cmn_connect::insert($cmn_connect);
    }
}
