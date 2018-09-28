<?php
use think\Route;
Route::get('api/:version/banner/:id','api/:version.banner/getBanner');//自由灵活的控制版本号
Route::get('api/:version/theme','api/:version.theme/getSimpleList');//自由灵活的控制版本号
Route::get('api/:version/theme/:id','api/:version.theme/getComplexOne');

Route::group('api/:version/product',function (){
    Route::get('/recent','api/:version.product/getRecent');
    Route::get('/cate_id','api/:version.product/getAllInCategory');
    Route::get('/:id','api/:version.product/getOneItem',[],['id'=>'\d+']); //正则限定
});
Route::get('api/:version/category/all','api/:version.category/getAllCategories');
Route::post('api/:version/token/user','api/:version.token/getToken');
Route::post('api/:version/address','api/:version.address/createOrUpdateAddress');

Route::post('api/:version/order','api/:version.order/placeOrder');
Route::get('api/:version/by_order','api/:version.order/getSummaryByUser');
Route::get('api/:version/order/:id','api/:version.order/getDetail',[],['id'=>'\d+']);

Route::post('api/:version/pay/pre_order','api/:version.pay/getPreOrder');
Route::post('api/:version/pay/notify','api/:version.pay/receiveNotify');
