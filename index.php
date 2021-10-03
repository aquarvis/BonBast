<?php
//========== Header ==========
error_reporting(false);
header('Content-Type: application/json;charset=utf-8');
//========== Get Info's Of 1 Currency ==========
if(isset($_GET['arz'])){
    if($_GET['arz'] != 'all'){
        $arz = file_get_contents('https://bonbast.com/graph/'.strtolower($_GET['arz']));
        preg_match_all('#<td class="price">(.*?)</td>#i',$arz,$res);
        preg_match_all('#<span class="miladi">(.*?)</span>#i',$arz,$res1);
        preg_match_all('#<option value="(.*?)" selected>(.*?)</option>#i',$arz,$res2);
        if(strtolower($_GET['arz']) != 'usd' && $res1[1][0] == 'USD/IRR'){
            preg_match_all('#<option value="(.*?)" >(.*?)</option>#i',$arz,$res3);
            $arr=array();
            $arr[]='USD: US Dollar';
            foreach($res3[2] as $ali){
                $arr[]=$ali;
            }
            echo json_encode(array_merge([
                'ok'=> false,
                'channel'=> "@LegacySource",
                'writer'=> "@Aquarvis",
                'results'=>' arz Parameter Is Invalid !',
                'currency_list'=>$arr
                ]
            )
        , 448);
        }else{
            echo json_encode(array_merge([
                'ok'=> true,
                'channel'=> "@LegacySource",
                'writer'=> "@Aquarvis",
                'results'=>
                    [
                        'name'=>$res1[1][0],
                        'full_name'=>$res2[2][0],
                        'min_price_sell'=>number_format($res[1][4]),
                        'min_price_buy'=>number_format($res[1][5]),
                        'avg_price_sell'=>number_format($res[1][0]),
                        'avg_price_buy'=>number_format($res[1][1]),
                        'max_price_sell'=>number_format($res[1][2]),
                        'max_price_buy'=>number_format($res[1][3]),
                    ]
                ]
            )
        , 448);
        }
    }elseif($_GET['arz'] == 'all'){
        $all=array();
        $names = ['usd','eur','gbp','try','rub','cad','omr','aed','chf'];
        foreach($names as $alireza){
            $arz = file_get_contents('https://bonbast.com/graph/'.$alireza);
            preg_match_all('#<td class="price">(.*?)</td>#i',$arz,$res);
            preg_match_all('#<span class="miladi">(.*?)</span>#i',$arz,$res1);
            preg_match_all('#<option value="(.*?)" selected>(.*?)</option>#i',$arz,$res2);
            $res = [
                    'name'=>$res1[1][0],
                    'full_name'=>$res2[2][0],
                    'min_price_sell'=>number_format($res[1][4]),
                    'min_price_buy'=>number_format($res[1][5]),
                    'avg_price_sell'=>number_format($res[1][0]),
                    'avg_price_buy'=>number_format($res[1][1]),
                    'max_price_sell'=>number_format($res[1][2]),
                    'max_price_buy'=>number_format($res[1][3]),
                ];
            $all[]=$res;
        }
        echo json_encode(array_merge([
                'ok'=> true,
                'channel'=> "@LegacySource",
                'writer'=> "@Aquarvis",
                'results'=>$all
                ]
            )
        , 448);
    }
}else{
    echo json_encode(array_merge([
            'ok'=> false,
            'channel'=> "@LegacySource",
            'writer'=> "@Aquarvis",
            'results'=>'Where Is Foucking arz Parameter ?!'
            ]
        )
    ,448);
}
//========== UnLink ==========
unlink('error_log');