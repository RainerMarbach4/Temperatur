<?php

/**
 * Created by PhpStorm.
 * User: rainer
 * Date: 25.03.2017
 * Time: 14:16
 */
class html
{

    public function htmlAnf() {
        $ret = '<html><head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                    <link rel=\'stylesheet\' href=\'css\first.css\'>
                    </head >
                    <body >
        ';
        echo $ret;
    }

    public function htmlEnd(){
        $ret ='
            </body >
            </html >
        ';
        echo $ret;
    }
}
