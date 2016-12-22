<?php

        fwrite(STDOUT,"Please input key you want to search:(1 for 'supplyment' 2 for 'requirement'  3 for 'agent') ");
        $key = trim(fgets(STDIN));
        switch($key){
                case 1:
                        $key='supplyment';
                        break;
                case 2:
                        $key='requirement';
                        break;
                case 3:
                        $key='agent';
                        break;
                default:
                        exit;
        }
        echo $key."\n";


        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $geolist=$redis->georadius($key,121.48789949,31.24916171,'60','km');
        echo "redis_ids are:";
        print_r($geolist);

        echo "points are:";
        foreach($geolist as $geo){
                $point=$redis->geopos($key,$geo);
                // echo 'lng is '.$point[0][0]."\n";
                // echo 'lat is '.$point[0][1]."\n";
                print_r($point);
                echo 'redis_id is '.$geo."\n\n";
        }


        while(1){

                fwrite(STDOUT,"Please input redis_id you want to remove:(type exit to quit, all to remove all)");
                $redis_id = trim(fgets(STDIN));
                if($redis_id=='exit'){
                        $redis->close();
                        exit;
                }
                if($redis_id=='all'){
                        $geolist=$redis->georadius($key,121.48789949,31.24916171,'35','km');
                        foreach($geolist as $geo){
                                $redis->zrem($key,$geo);
                        }
                }
                echo "redis_id to delete is ".$redis_id."\n";

                $redis->zrem($key,$redis_id);

                $geolist=$redis->georadius($key,121.48789949,31.24916171,'35','km');
                echo "redis_ids are:";
                print_r($geolist);

                echo "points are:";
                foreach($geolist as $geo){
                        $point=$redis->geopos($key,$geo);
                        print_r($point);
                }
        }