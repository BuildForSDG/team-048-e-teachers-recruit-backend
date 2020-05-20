<?php

function getUniqueToken(){
    return substr(random_int(100000, 9999999),0,4);
}
