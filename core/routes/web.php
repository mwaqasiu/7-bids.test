<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::prefix('admintasks-wts-1254373456745523523')->group(function () {
    Route::get('/clear', function () {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    });
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::namespace('Gateway')->prefix('ipn')->name('ipn.')->group(function () {
    Route::post('paypal', 'Paypal\ProcessController@ipn')->name('Paypal');
    Route::get('paypal-sdk', 'PaypalSdk\ProcessController@ipn')->name('PaypalSdk');
    Route::post('perfect-money', 'PerfectMoney\ProcessController@ipn')->name('PerfectMoney');
    Route::post('stripe', 'Stripe\ProcessController@ipn')->name('Stripe');
    Route::post('stripe-js', 'StripeJs\ProcessController@ipn')->name('StripeJs');
    Route::post('stripe-v3', 'StripeV3\ProcessController@ipn')->name('StripeV3');
    Route::post('skrill', 'Skrill\ProcessController@ipn')->name('Skrill');
    Route::post('paytm', 'Paytm\ProcessController@ipn')->name('Paytm');
    Route::post('payeer', 'Payeer\ProcessController@ipn')->name('Payeer');
    Route::post('paystack', 'Paystack\ProcessController@ipn')->name('Paystack');
    Route::post('voguepay', 'Voguepay\ProcessController@ipn')->name('Voguepay');
    Route::get('flutterwave/{trx}/{type}', 'Flutterwave\ProcessController@ipn')->name('Flutterwave');
    Route::post('razorpay', 'Razorpay\ProcessController@ipn')->name('Razorpay');
    Route::post('instamojo', 'Instamojo\ProcessController@ipn')->name('Instamojo');
    Route::get('blockchain', 'Blockchain\ProcessController@ipn')->name('Blockchain');
    Route::get('blockio', 'Blockio\ProcessController@ipn')->name('Blockio');
    Route::post('coinpayments', 'Coinpayments\ProcessController@ipn')->name('Coinpayments');
    Route::post('coinpayments-fiat', 'Coinpayments_fiat\ProcessController@ipn')->name('CoinpaymentsFiat');
    Route::post('coingate', 'Coingate\ProcessController@ipn')->name('Coingate');
    Route::post('coinbase-commerce', 'CoinbaseCommerce\ProcessController@ipn')->name('CoinbaseCommerce');
    Route::get('mollie', 'Mollie\ProcessController@ipn')->name('Mollie');
    Route::post('cashmaal', 'Cashmaal\ProcessController@ipn')->name('Cashmaal');
    Route::post('authorize-net', 'AuthorizeNet\ProcessController@ipn')->name('AuthorizeNet');
    Route::post('2check-out', 'TwoCheckOut\ProcessController@ipn')->name('TwoCheckOut');
    Route::post('mercado-pago', 'MercadoPago\ProcessController@ipn')->name('MercadoPago');
});

// User Support Ticket
Route::prefix('ticket')->group(function () {
    Route::get('/', 'TicketController@supportTicket')->name('ticket');
    Route::get('/ticket/delete/{id}', 'TicketController@deleteSupportTicket')->name('ticket.delete');
    Route::get('/new', 'TicketController@openSupportTicket')->name('ticket.open');
    Route::post('/create', 'TicketController@storeSupportTicket')->name('ticket.store');
    Route::get('/view/{ticket}', 'TicketController@viewTicket')->name('ticket.view');
    Route::post('/reply/{ticket}', 'TicketController@replyTicket')->name('ticket.reply');
    Route::get('/download/{ticket}', 'TicketController@ticketDownload')->name('ticket.download');
});


/*
|--------------------------------------------------------------------------
| Start Admin Area
|--------------------------------------------------------------------------
*/

Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');
        // Admin Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetCodeEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify.code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset.form');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });

    Route::middleware('admin')->group(function () {
        Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');
        Route::get('profile', 'AdminController@profile')->name('profile');
        Route::post('profile', 'AdminController@profileUpdate')->name('profile.update');
        Route::get('password', 'AdminController@password')->name('password');
        Route::post('password', 'AdminController@passwordUpdate')->name('password.update');

        //Notification
        Route::get('notifications','AdminController@notifications')->name('notifications');
        Route::get('notifications/onedelete/{id}', 'AdminController@notificationOneDelete')->name('notification.onedelete');
        Route::get('notifications/delete', 'AdminController@notificationDelete')->name('notification.delete');
        Route::get('notification/read/{id}','AdminController@notificationRead')->name('notification.read');
        Route::get('notifications/read-all','AdminController@readAll')->name('notifications.readAll');

        //Report Bugs
        Route::get('request-report','AdminController@requestReport')->name('request.report');
        Route::post('request-report','AdminController@reportSubmit');

        Route::get('system-info','AdminController@systemInfo')->name('system.info');

        Route::post('question/answering', 'AdminController@questionanswering')->name('question.answering');

        //Manage Category
        Route::get('categories', 'CategoryController@index')->name('categories');
        Route::post('category/store/{id?}', 'CategoryController@saveCategory')->name('category.store');
        Route::post('category/delete', 'CategoryController@deleteCategory')->name('category.delete');

        // Switch or Rest User language
        Route::get('reset/language', 'AdminController@resetlanguage')->name('reset.user.language');

        // Manage Auction
        Route::get('auctions/all', 'AuctionController@index')->name('product.auction.index');
        Route::get('auctions/add', 'AuctionController@create')->name('product.auction.create');
        Route::post('store-auction', 'AuctionController@store')->name('product.auction.store');
        Route::post('store-auction-one-image', 'AuctionController@storeoneimg')->name('product.auction.oneimage');
        Route::post('auctions/delete', 'AuctionController@deleteAuction')->name('product.auction.delete');
        Route::post('approve-auction', 'AuctionController@approve')->name('product.auction.approve');
        Route::get('auction/edit/{id}', 'AuctionController@edit')->name('product.auction.edit');
        Route::get('auction/startagain/{id}', 'AuctionController@startagain')->name('product.auction.startagain');
        Route::post('update-auction/{id}', 'AuctionController@update')->name('product.auction.update');
        Route::get('auction/{id}/bids', 'AuctionController@auctionBids')->name('product.auction.bids');
        Route::post('auctionbid/winner', 'AuctionController@auctionbidWinner')->name('auction.bid.winner');
        Route::get('auction/winners', 'AuctionController@auctionsWinner')->name('product.auction.winners');
        Route::get('auction/decline/{bid_id}', 'AuctionController@declinewinner')->name('product.auction.decline');
        Route::post('auction/paid', 'AuctionController@paidAuction')->name('auction.paid');
        Route::post('auction/picked', 'AuctionController@pickedAuction')->name('auction.picked');
        Route::post('auction/packed', 'AuctionController@packedAuction')->name('auction.packed');
        Route::post('auction/transited', 'AuctionController@transitedAuction')->name('auction.transited');
        Route::post('auction/generateshipurl', 'AuctionController@generatedShipUrl')->name('auction.generateshipurl');
        Route::post('auction/winneritemdelete', 'AuctionController@winneritemdelete')->name('auction.winnerdelete');
        Route::post('store-auction-onestatus-image', 'AuctionController@storestatusimg')->name('auction.onestatusimage');
        // End Manage auction

        //Manage Product
        Route::get('products/all', 'ProductController@index')->name('product.index');
        Route::get('products/live', 'ProductController@index')->name('product.live');
        Route::get('products/pending', 'ProductController@index')->name('product.pending');
        Route::get('products/upcoming', 'ProductController@index')->name('product.upcoming');
        Route::get('products/expired', 'ProductController@index')->name('product.expired');
        Route::post('approve-product', 'ProductController@approve')->name('product.approve');
        Route::get('products/add', 'ProductController@create')->name('product.create');
        Route::post('store-product', 'ProductController@store')->name('product.store');
        Route::post('store-product-one-image', 'ProductController@storeoneimg')->name('product.oneimage');
        Route::post('store-product-onestatus-image', 'ProductController@storestatusimg')->name('product.onestatusimage');
        Route::get('product/edit/{id}', 'ProductController@edit')->name('product.edit');
        Route::get('product/startagain/{id}', 'ProductController@startagain')->name('product.startagain');
        Route::post('update-product/{id}', 'ProductController@update')->name('product.update');
        Route::get('product/{id}/bids', 'ProductController@productBids')->name('product.bids');
        Route::get('bid/decline/{bid_id}', 'ProductController@declinewinner')->name('bid.decline');
        Route::post('bid/winner', 'ProductController@bidWinner')->name('bid.winner');
        Route::get('product/winners', 'ProductController@productWinner')->name('product.winners');
        Route::post('product/delivered', 'ProductController@deliveredProduct')->name('product.delivered');
        Route::post('product/paid', 'ProductController@paidProduct')->name('product.paid');
        Route::post('product/picked', 'ProductController@pickedProduct')->name('product.picked');
        Route::post('product/packed', 'ProductController@packedProduct')->name('product.packed');
        Route::post('product/transited', 'ProductController@transitedProduct')->name('product.transited');
        Route::post('product/generateshipurl', 'ProductController@generatedShipUrl')->name('product.generateshipurl');
        Route::post('products/delete', 'ProductController@deleteProduct')->name('product.delete');
        Route::get('products/search', 'ProductController@index')->name('product.search');
        Route::post('products/winneritemdelete', 'ProductController@winneritemdelete')->name('product.winnerdelete');

        //Manage Advertisement
        Route::get('advertisement', 'AdvertisementController@index')->name('advertisement.index');
        Route::get('advertisement/create', 'AdvertisementController@create')->name('advertisement.create');
        Route::post('advertisement/store', 'AdvertisementController@store')->name('advertisement.store');
        Route::post('advertisement/update/{id}', 'AdvertisementController@update')->name('advertisement.update');
        Route::post('advertisement/delete', 'AdvertisementController@delete')->name('advertisement.delete');

        // Users Manager
        Route::get('users', 'ManageUsersController@allUsers')->name('users.all');
        Route::get('users/active', 'ManageUsersController@activeUsers')->name('users.active');
        Route::get('users/banned', 'ManageUsersController@bannedUsers')->name('users.banned');
        Route::get('users/email-verified', 'ManageUsersController@emailVerifiedUsers')->name('users.email.verified');
        Route::get('users/email-unverified', 'ManageUsersController@emailUnverifiedUsers')->name('users.email.unverified');
        Route::get('users/sms-unverified', 'ManageUsersController@smsUnverifiedUsers')->name('users.sms.unverified');
        Route::get('users/sms-verified', 'ManageUsersController@smsVerifiedUsers')->name('users.sms.verified');
        Route::get('users/with-balance', 'ManageUsersController@usersWithBalance')->name('users.with.balance');

        Route::get('users/{scope}/search', 'ManageUsersController@search')->name('users.search');
        Route::get('user/detail/{id}', 'ManageUsersController@detail')->name('users.detail');
        Route::post('user/delete', 'ManageUsersController@deleteuser')->name('users.delete');
        Route::post('user/update/{id}', 'ManageUsersController@update')->name('users.update');
        Route::post('user/add-sub-bonus/{id}', 'ManageUsersController@addSubBonus')->name('users.add.sub.bonus');
        Route::post('user/add-sub-balance/{id}', 'ManageUsersController@addSubBalance')->name('users.add.sub.balance');
        Route::get('user/send-email/{id}', 'ManageUsersController@showEmailSingleForm')->name('users.email.single');
        Route::post('user/send-email/{id}', 'ManageUsersController@sendEmailSingle')->name('users.email.single');
        Route::get('user/login/{id}', 'ManageUsersController@login')->name('users.login');
        Route::get('user/transactions/{id}', 'ManageUsersController@transactions')->name('users.transactions');
        Route::get('user/deposits/{id}', 'ManageUsersController@deposits')->name('users.deposits');
        Route::get('user/deposits/via/{method}/{type?}/{userId}', 'ManageUsersController@depositViaMethod')->name('users.deposits.method');
        Route::get('user/bids/{id}', 'ManageUsersController@bids')->name('users.bids');


        // Merchants Manager
        Route::get('merchants', 'ManageMerchantsController@allMerchants')->name('merchants.all');
        Route::get('merchants/active', 'ManageMerchantsController@activeMerchants')->name('merchants.active');
        Route::get('merchants/banned', 'ManageMerchantsController@bannedMerchants')->name('merchants.banned');
        Route::get('merchants/email-verified', 'ManageMerchantsController@emailVerifiedMerchants')->name('merchants.email.verified');
        Route::get('merchants/email-unverified', 'ManageMerchantsController@emailUnverifiedMerchants')->name('merchants.email.unverified');
        Route::get('merchants/sms-unverified', 'ManageMerchantsController@smsUnverifiedMerchants')->name('merchants.sms.unverified');
        Route::get('merchants/sms-verified', 'ManageMerchantsController@smsVerifiedMerchants')->name('merchants.sms.verified');
        Route::get('merchants/with-balance', 'ManageMerchantsController@merchantsWithBalance')->name('merchants.with.balance');

        Route::get('merchants/{scope}/search', 'ManageMerchantsController@search')->name('merchants.search');
        Route::get('merchant/detail/{id}', 'ManageMerchantsController@detail')->name('merchants.detail');
        Route::post('merchant/delete', 'ManageMerchantsController@deleteseller')->name('merchants.delete');
        Route::post('merchant/update/{id}', 'ManageMerchantsController@update')->name('merchants.update');
        Route::post('merchant/add-sub-balance/{id}', 'ManageMerchantsController@addSubBalance')->name('merchants.add.sub.balance');
        Route::get('merchant/send-email/{id}', 'ManageMerchantsController@showEmailSingleForm')->name('merchants.email.single');
        Route::post('merchant/send-email/{id}', 'ManageMerchantsController@sendEmailSingle')->name('merchants.email.single');
        Route::get('merchant/login/{id}', 'ManageMerchantsController@login')->name('merchants.login');
        Route::get('merchant/transactions/{id}', 'ManageMerchantsController@transactions')->name('merchants.transactions');
        Route::get('merchant/products/{id}', 'ManageMerchantsController@products')->name('merchants.products');
        Route::get('merchant/payments/via/{method}/{type?}/{merchantId}', 'ManageMerchantsController@depositViaMethod')->name('merchants.deposits.method');
        Route::get('merchant/withdrawals/{id}', 'ManageMerchantsController@withdrawals')->name('merchants.withdrawals');
        Route::get('merchant/withdrawals/via/{method}/{type?}/{merchantId}', 'ManageMerchantsController@withdrawalsViaMethod')->name('merchants.withdrawals.method');



        // Login History
        Route::get('users/login/history/{id}', 'ManageUsersController@userLoginHistory')->name('users.login.history.single');

        Route::get('users/send-email', 'ManageUsersController@showEmailAllForm')->name('users.email.all');
        Route::post('users/send-email', 'ManageUsersController@sendEmailAll')->name('users.email.send');
        Route::get('users/email-log/{id}', 'ManageUsersController@emailLog')->name('users.email.log');
        Route::get('users/email-details/{id}', 'ManageUsersController@emailDetails')->name('users.email.details');

        // Merchant Login History
        Route::get('merchants/login/history/{id}', 'ManageMerchantsController@merchantLoginHistory')->name('merchants.login.history.single');

        Route::get('merchants/send-email', 'ManageMerchantsController@showEmailAllForm')->name('merchants.email.all');
        Route::post('merchants/send-email', 'ManageMerchantsController@sendEmailAll')->name('merchants.email.send');
        Route::get('merchants/email-log/{id}', 'ManageMerchantsController@emailLog')->name('merchants.email.log');
        Route::get('merchants/email-details/{id}', 'ManageMerchantsController@emailDetails')->name('merchants.email.details');

        // Deposit Gateway
        Route::name('gateway.')->prefix('gateway')->group(function(){
            // Automatic Gateway
            Route::get('automatic', 'GatewayController@index')->name('automatic.index');
            Route::get('automatic/edit/{alias}', 'GatewayController@edit')->name('automatic.edit');
            Route::post('automatic/update/{code}', 'GatewayController@update')->name('automatic.update');
            Route::post('automatic/remove/{code}', 'GatewayController@remove')->name('automatic.remove');
            Route::post('automatic/activate', 'GatewayController@activate')->name('automatic.activate');
            Route::post('automatic/deactivate', 'GatewayController@deactivate')->name('automatic.deactivate');


            // Manual Methods
            Route::get('manual', 'ManualGatewayController@index')->name('manual.index');
            Route::get('manual/new', 'ManualGatewayController@create')->name('manual.create');
            Route::post('manual/new', 'ManualGatewayController@store')->name('manual.store');
            Route::get('manual/edit/{alias}', 'ManualGatewayController@edit')->name('manual.edit');
            Route::post('manual/update/{id}', 'ManualGatewayController@update')->name('manual.update');
            Route::post('manual/activate', 'ManualGatewayController@activate')->name('manual.activate');
            Route::post('manual/deactivate', 'ManualGatewayController@deactivate')->name('manual.deactivate');
        });


        // DEPOSIT SYSTEM
        Route::name('deposit.')->prefix('deposit')->group(function(){
            Route::get('/', 'DepositController@deposit')->name('list');
            Route::get('pending', 'DepositController@pending')->name('pending');
            Route::get('rejected', 'DepositController@rejected')->name('rejected');
            Route::get('approved', 'DepositController@approved')->name('approved');
            Route::get('successful', 'DepositController@successful')->name('successful');
            Route::get('details/{id}', 'DepositController@details')->name('details');

            Route::post('reject', 'DepositController@reject')->name('reject');
            Route::post('approve', 'DepositController@approve')->name('approve');
            Route::get('via/{method}/{type?}', 'DepositController@depositViaMethod')->name('method');
            Route::get('/{scope}/search', 'DepositController@search')->name('search');
            Route::get('date-search/{scope}', 'DepositController@dateSearch')->name('dateSearch');

        });


        // WITHDRAW SYSTEM
        Route::name('withdraw.')->prefix('withdraw')->group(function(){
            Route::get('pending', 'WithdrawalController@pending')->name('pending');
            Route::get('approved', 'WithdrawalController@approved')->name('approved');
            Route::get('rejected', 'WithdrawalController@rejected')->name('rejected');
            Route::get('log', 'WithdrawalController@log')->name('log');
            Route::get('via/{method_id}/{type?}', 'WithdrawalController@logViaMethod')->name('method');
            Route::get('{scope}/search', 'WithdrawalController@search')->name('search');
            Route::get('date-search/{scope}', 'WithdrawalController@dateSearch')->name('dateSearch');
            Route::get('details/{id}', 'WithdrawalController@details')->name('details');
            Route::post('approve', 'WithdrawalController@approve')->name('approve');
            Route::post('reject', 'WithdrawalController@reject')->name('reject');


            // Withdraw Method
            Route::get('method/', 'WithdrawMethodController@methods')->name('method.index');
            Route::get('method/create', 'WithdrawMethodController@create')->name('method.create');
            Route::post('method/create', 'WithdrawMethodController@store')->name('method.store');
            Route::get('method/edit/{id}', 'WithdrawMethodController@edit')->name('method.edit');
            Route::post('method/edit/{id}', 'WithdrawMethodController@update')->name('method.update');
            Route::post('method/activate', 'WithdrawMethodController@activate')->name('method.activate');
            Route::post('method/deactivate', 'WithdrawMethodController@deactivate')->name('method.deactivate');
        });

        // Report
        Route::get('report/user/transaction', 'ReportController@userTransaction')->name('report.user.transaction');
        Route::get('report/user/transaction/search', 'ReportController@userTransactionSearch')->name('report.user.transaction.search');
        Route::get('report/merchant/transaction', 'ReportController@merchantTransaction')->name('report.merchant.transaction');
        Route::get('report/merchant/transaction/search', 'ReportController@merchantTransactionSearch')->name('report.merchant.transaction.search');
        Route::get('report/user/login/history', 'ReportController@userLoginHistory')->name('report.user.login.history');
        Route::get('report/user/login/ipHistory/{ip}', 'ReportController@userLoginIpHistory')->name('report.user.login.ipHistory');
        Route::get('report/merchant/login/history', 'ReportController@merchantLoginHistory')->name('report.merchant.login.history');
        Route::get('report/merchant/login/ipHistory/{ip}', 'ReportController@merchantLoginIpHistory')->name('report.merchant.login.ipHistory');
        Route::get('report/user/email/history', 'ReportController@userEmailHistory')->name('report.user.email.history');
        Route::get('report/merchant/email/history', 'ReportController@merchantEmailHistory')->name('report.merchant.email.history');


        // Admin User Support
        Route::get('tickets/users', 'SupportTicketController@userTickets')->name('user.ticket');
        Route::get('tickets/users/pending', 'SupportTicketController@userTendingTicket')->name('user.ticket.pending');
        Route::get('tickets/users/closed', 'SupportTicketController@userClosedTicket')->name('user.ticket.closed');
        Route::get('tickets/users/answered', 'SupportTicketController@userAnsweredTicket')->name('user.ticket.answered');
        Route::get('tickets/users/view/{id}', 'SupportTicketController@userTicketReply')->name('user.ticket.view');
        Route::post('ticket/users/reply/{id}', 'SupportTicketController@userTicketReplySend')->name('user.ticket.reply');
        Route::get('ticket/users/download/{ticket}', 'SupportTicketController@userTicketDownload')->name('user.ticket.download');
        Route::post('ticket/users/delete', 'SupportTicketController@userTicketDelete')->name('user.ticket.delete');

        // Admin Merhchant Support
        Route::get('tickets/merchants', 'SupportTicketController@merchantTickets')->name('merchant.ticket');
        Route::get('tickets/merchants/pending', 'SupportTicketController@merchantTendingTicket')->name('merchant.ticket.pending');
        Route::get('tickets/merchants/closed', 'SupportTicketController@merchantClosedTicket')->name('merchant.ticket.closed');
        Route::get('tickets/merchants/answered', 'SupportTicketController@merchantAnsweredTicket')->name('merchant.ticket.answered');
        Route::get('tickets/merchants/view/{id}', 'SupportTicketController@merchantTicketReply')->name('merchant.ticket.view');
        Route::post('ticket/merchants/reply/{id}', 'SupportTicketController@merchantTicketReplySend')->name('merchant.ticket.reply');
        Route::get('ticket/merchants/download/{ticket}', 'SupportTicketController@merchantTicketDownload')->name('merchant.ticket.download');
        Route::post('ticket/merchants/delete', 'SupportTicketController@merchantTicketDelete')->name('merchant.ticket.delete');


        // Language Manager
        Route::get('/language', 'LanguageController@langManage')->name('language.manage');
        Route::post('/language', 'LanguageController@langStore')->name('language.manage.store');
        Route::post('/language/delete/{id}', 'LanguageController@langDel')->name('language.manage.del');
        Route::post('/language/update/{id}', 'LanguageController@langUpdate')->name('language.manage.update');
        Route::get('/language/edit/{id}', 'LanguageController@langEdit')->name('language.key');
        Route::post('/language/import', 'LanguageController@langImport')->name('language.importLang');



        Route::post('language/store/key/{id}', 'LanguageController@storeLanguageJson')->name('language.store.key');
        Route::post('language/delete/key/{id}', 'LanguageController@deleteLanguageJson')->name('language.delete.key');
        Route::post('language/update/key/{id}', 'LanguageController@updateLanguageJson')->name('language.update.key');



        // General Setting
        Route::get('general-setting', 'GeneralSettingController@index')->name('setting.index');
        Route::post('general-setting', 'GeneralSettingController@update')->name('setting.update');
        Route::get('optimize', 'GeneralSettingController@optimize')->name('setting.optimize');
        Route::get('merchant-profile', 'GeneralSettingController@merchantProfile')->name('merchant.profile');
        Route::post('merchant-profile', 'GeneralSettingController@merchantProfileSubmit')->name('merchant.profile');

        // Logo-Icon
        Route::get('setting/logo-icon', 'GeneralSettingController@logoIcon')->name('setting.logo.icon');
        Route::post('setting/logo-icon', 'GeneralSettingController@logoIconUpdate')->name('setting.logo.icon');

        //Custom CSS
        Route::get('custom-css','GeneralSettingController@customCss')->name('setting.custom.css');
        Route::post('custom-css','GeneralSettingController@customCssSubmit');


        //Cookie
        Route::get('cookie','GeneralSettingController@cookie')->name('setting.cookie');
        Route::post('cookie','GeneralSettingController@cookieSubmit');

        //News
        Route::get('news', 'GeneralSettingController@news')->name('news');
        Route::post('news','GeneralSettingController@newsSubmit');

        //Sliders
        Route::get('sliders', 'GeneralSettingController@sliders')->name('sliders');
        Route::post('sliders', 'GeneralSettingController@addSliders')->name('add.sliders');
        Route::post('sliders/delete', 'GeneralSettingController@delSliders')->name('delete.sliders');
        Route::post('sliders/pending', 'GeneralSettingController@pendingSliders')->name('pending.sliders');
        Route::post('sliders/live', 'GeneralSettingController@liveSliders')->name('live.sliders');

        //Front Sliders
        Route::get('frontsliders', 'GeneralSettingController@frontsliders')->name('frontsliders');
        Route::post('frontsliders', 'GeneralSettingController@addFrontsliders')->name('add.frontsliders');
        Route::post('frontsliders/delete', 'GeneralSettingController@delFrontsliders')->name('delete.frontsliders');
        Route::post('frontsliders/pending', 'GeneralSettingController@pendingFrontsliders')->name('pending.frontsliders');
        Route::post('frontsliders/live', 'GeneralSettingController@liveFrontsliders')->name('live.frontsliders');

        //Charity Project
        Route::get('charity', 'GeneralSettingController@charity')->name('charity');
        Route::post('charity', 'GeneralSettingController@charitySubmit');

        Route::get('payment-methods', 'GeneralSettingController@paymentmethod')->name('paymentmethod');
        Route::post('payment-methods/store/{id?}', 'GeneralSettingController@paymentmethodstore')->name('paymentmethod.store');
        Route::post('payment-methods/delete', 'GeneralSettingController@deletePaymentmethod')->name('paymentmethod.delete');

        // Plugin
        Route::get('extensions', 'ExtensionController@index')->name('extensions.index');
        Route::post('extensions/update/{id}', 'ExtensionController@update')->name('extensions.update');
        Route::post('extensions/activate', 'ExtensionController@activate')->name('extensions.activate');
        Route::post('extensions/deactivate', 'ExtensionController@deactivate')->name('extensions.deactivate');

        // Email Setting
        Route::get('email-template/global', 'EmailTemplateController@emailTemplate')->name('email.template.global');
        Route::post('email-template/global', 'EmailTemplateController@emailTemplateUpdate')->name('email.template.global');
        Route::get('email-template/setting', 'EmailTemplateController@emailSetting')->name('email.template.setting');
        Route::post('email-template/setting', 'EmailTemplateController@emailSettingUpdate')->name('email.template.setting');
        Route::get('email-template/index', 'EmailTemplateController@index')->name('email.template.index');
        Route::get('email-template/{id}/edit', 'EmailTemplateController@edit')->name('email.template.edit');
        Route::post('email-template/{id}/update', 'EmailTemplateController@update')->name('email.template.update');
        Route::post('email-template/send-test-mail', 'EmailTemplateController@sendTestMail')->name('email.template.test.mail');

        // SMS Setting
        Route::get('sms-template/global', 'SmsTemplateController@smsTemplate')->name('sms.template.global');
        Route::post('sms-template/global', 'SmsTemplateController@smsTemplateUpdate')->name('sms.template.global');
        Route::get('sms-template/setting','SmsTemplateController@smsSetting')->name('sms.templates.setting');
        Route::post('sms-template/setting', 'SmsTemplateController@smsSettingUpdate')->name('sms.template.setting');
        Route::get('sms-template/index', 'SmsTemplateController@index')->name('sms.template.index');
        Route::get('sms-template/edit/{id}', 'SmsTemplateController@edit')->name('sms.template.edit');
        Route::post('sms-template/update/{id}', 'SmsTemplateController@update')->name('sms.template.update');
        Route::post('email-template/send-test-sms', 'SmsTemplateController@sendTestSMS')->name('sms.template.test.sms');

        // SEO
        Route::get('seo', 'FrontendController@seoEdit')->name('seo');

        // Frontend
        Route::name('frontend.')->prefix('frontend')->group(function () {


            Route::get('templates', 'FrontendController@templates')->name('templates');
            Route::post('templates', 'FrontendController@templatesActive')->name('templates.active');


            Route::get('frontend-sections/{key}', 'FrontendController@frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'FrontendController@frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'FrontendController@frontendElement')->name('sections.element');
            Route::post('remove', 'FrontendController@remove')->name('remove');

            // Page Builder
            Route::get('manage-pages', 'PageBuilderController@managePages')->name('manage.pages');
            Route::post('manage-pages', 'PageBuilderController@managePagesSave')->name('manage.pages.save');
            Route::post('manage-pages/update', 'PageBuilderController@managePagesUpdate')->name('manage.pages.update');
            Route::post('manage-pages/delete', 'PageBuilderController@managePagesDelete')->name('manage.pages.delete');
            Route::get('manage-section/{id}', 'PageBuilderController@manageSection')->name('manage.section');
            Route::post('manage-section/{id}', 'PageBuilderController@manageSectionUpdate')->name('manage.section.update');
        });
    });
});


/*
|--------------------------------------------------------------------------
| Start Merchant Area
|--------------------------------------------------------------------------
*/





Route::namespace('Merchant')->prefix('merchant')->name('merchant.')->group(function(){
    Route::namespace('Auth')->group(function(){
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');

        Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'RegisterController@register')->middleware('regStatus');
        Route::post('check-mail', 'RegisterController@checkUser')->name('checkUser');

        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'ForgotPasswordController@sendResetCodeEmail')->name('password.email');
        Route::get('password/code-verify', 'ForgotPasswordController@codeVerify')->name('password.code.verify');
        Route::post('password/reset', 'ResetPasswordController@reset')->name('password.update');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify.code');
    });

    Route::middleware('merchant')->group(function(){

        Route::get('authorization', 'AuthorizationController@authorizeForm')->name('authorization');
        Route::get('resend-verify', 'AuthorizationController@sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'AuthorizationController@emailVerification')->name('verify.email');
        Route::post('verify-sms', 'AuthorizationController@smsVerification')->name('verify.sms');
        Route::post('verify-g2fa', 'AuthorizationController@g2faVerification')->name('go2fa.verify');

        Route::middleware('merchant.checkStatus')->group(function(){

            Route::get('dashboard', 'MerchantController@dashboard')->name('dashboard');

            // Manage Auction
            Route::get('auctions/all', 'AuctionController@index')->name('product.auction.index');
            Route::get('auctions/add', 'AuctionController@create')->name('product.auction.create');
            Route::get('auction/edit/{id}', 'AuctionController@edit')->name('product.auction.edit');
            Route::post('store-auction', 'AuctionController@store')->name('product.auction.store');
            Route::post('update-auction/{id}', 'AuctionController@update')->name('product.auction.update');
            Route::post('approve-auction', 'AuctionController@approve')->name('product.auction.approve');
            Route::get('auction/startagain/{id}', 'AuctionController@startagain')->name('product.auction.startagain');
            Route::post('store-auction-one-image', 'AuctionController@storeoneimg')->name('product.auction.oneimage');
            Route::post('store-auctioin-onestatus-image', 'AuctionController@storestatusimg')->name('product.auction.onestatusimage');
            Route::post('auctions/delete', 'AuctionController@deleteAuction')->name('product.auction.delete');
            Route::get('auction/{id}/bids', 'AuctionController@auctionBids')->name('product.auction.bids');
            Route::post('auctionbid/winner', 'AuctionController@auctionbidWinner')->name('auction.bid.winner');
            // End Manage Auction

            //Manage Product
            Route::get('products/all', 'ProductController@index')->name('product.index');
            Route::get('products/live', 'ProductController@index')->name('product.live');
            Route::get('products/pending', 'ProductController@index')->name('product.pending');
            Route::get('products/upcoming', 'ProductController@index')->name('product.upcoming');
            Route::get('products/expired', 'ProductController@index')->name('product.expired');
            Route::post('approve-product', 'ProductController@approve')->name('product.approve');
            Route::post('products/delete', 'ProductController@deleteProduct')->name('product.delete');
            Route::get('products/search', 'ProductController@index')->name('product.search');
            Route::get('products/add', 'ProductController@create')->name('product.create');
            Route::post('store-product', 'ProductController@store')->name('product.store');
            Route::post('store-product-one-image', 'ProductController@storeoneimg')->name('product.oneimage');
            Route::post('store-product-onestatus-image', 'ProductController@storestatusimg')->name('product.onestatusimage');
            Route::get('product/edit/{id}', 'ProductController@edit')->name('product.edit');
            Route::get('product/startagain/{id}', 'ProductController@startagain')->name('product.startagain');
            Route::post('update-product/{id}', 'ProductController@update')->name('product.update');
            Route::get('product/{id}/bids', 'ProductController@productBids')->name('product.bids');
            Route::post('bid/winner', 'ProductController@bidWinner')->name('bid.winner');
            Route::get('bid/priceofferwinner/{bid_id}', 'ProductController@bidpriceofferwinner')->name('bid.priceofferwinner');
            Route::get('bid/priceofferdecline/{bid_id}', 'ProductController@bidpriceofferdecline')->name('bid.priceofferdecline');
            Route::get('product/winners', 'ProductController@productWinner')->name('bid.winners');
            Route::post('product/delivered', 'ProductController@deliveredProduct')->name('bid.delivered');

            Route::post('question/answering', 'MerchantController@questionanswering')->name('question.answering');

            Route::get('bid-logs', 'ProductController@bids')->name('bids');
            Route::get('transactions', 'MerchantController@transactions')->name('transactions');


            Route::get('profile', 'MerchantController@profile')->name('profile');
            Route::post('profile', 'MerchantController@profileUpdate')->name('profile.update');
            Route::get('change-password', 'MerchantController@changePassword')->name('change.password');
            Route::post('change-password', 'MerchantController@submitPassword');

            //2FA
            Route::get('twofactor', 'MerchantController@show2faForm')->name('twofactor');
            Route::post('twofactor/enable', 'MerchantController@create2fa')->name('twofactor.enable');
            Route::post('twofactor/disable', 'MerchantController@disable2fa')->name('twofactor.disable');

            Route::get('closeaccount', 'MerchantController@closeAccount')->name('closeaccount');
            Route::get('closeaccount/close', 'MerchantController@closeAccountclose')->name('closeaccount.close');


            // Withdraw
            Route::get('/withdraw', 'MerchantController@withdrawMoney')->name('withdraw');
            Route::post('/withdraw', 'MerchantController@withdrawStore')->name('withdraw.money');
            Route::get('/withdraw/preview', 'MerchantController@withdrawPreview')->name('withdraw.preview');
            Route::post('/withdraw/preview', 'MerchantController@withdrawSubmit')->name('withdraw.submit');
            Route::get('/withdraw/history', 'MerchantController@withdrawLog')->name('withdraw.history');

            // Merchant Support Ticket
            Route::prefix('ticket')->group(function () {
                Route::get('/', 'TicketController@supportTicket')->name('ticket');
                Route::get('/new', 'TicketController@openSupportTicket')->name('ticket.open');
                Route::post('/create', 'TicketController@storeSupportTicket')->name('ticket.store');
                Route::get('/view/{ticket}', 'TicketController@viewTicket')->name('ticket.view');
                Route::post('/reply/{ticket}', 'TicketController@replyTicket')->name('ticket.reply');
                Route::get('/download/{ticket}', 'TicketController@ticketDownload')->name('ticket.download');
            });
        });

    });
});





/*
|--------------------------------------------------------------------------
| Start User Area
|--------------------------------------------------------------------------
*/


Route::name('user.')->group(function () {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::get('/seller', 'Auth\LoginController@showSellerLoginForm')->name('sellerlogin');
    Route::post('/login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::get('seller/register', 'Auth\RegisterController@showSellerRegistrationForm')->name('sellerregister');
    Route::post('register', 'Auth\RegisterController@register')->middleware('regStatus');
    Route::post('check-mail', 'Auth\RegisterController@checkUser')->name('checkUser');

    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetCodeEmail')->name('password.email');
    Route::get('password/code-verify', 'Auth\ForgotPasswordController@codeVerify')->name('password.code.verify');
    Route::get('password/resend-code', 'Auth\ForgotPasswordController@resendCode')->name('password.resend.verify');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/verify-code', 'Auth\ForgotPasswordController@verifyCode')->name('password.verify.code');
});

Route::name('user.')->prefix('user')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('authorization', 'AuthorizationController@authorizeForm')->name('authorization');
        Route::get('resend-verify', 'AuthorizationController@sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'AuthorizationController@emailVerification')->name('verify.email');
        Route::post('verify-sms', 'AuthorizationController@smsVerification')->name('verify.sms');
        Route::post('verify-g2fa', 'AuthorizationController@g2faVerification')->name('go2fa.verify');

        Route::middleware(['checkStatus'])->group(function () {
            Route::get('dashboard', 'UserController@home')->name('home');
            Route::get('checkout/setting', 'UserController@checkout')->name('checkout');
            Route::post('checkout/save', 'UserController@checkoutSave')->name('checkout.save');
            Route::post('checkout/update', 'UserController@checkoutUpdate')->name('checkout.update');

            Route::get('leadingbid-history', 'UserController@leadingbidHistory')->name('leadingbid.history');
            Route::get('leadingbid-history/search/{skeys}', 'UserController@searchleadingbidHistory')->name('searchleadingbid.history');
            Route::post('delete/leadingbid-history-bid', 'UserController@deleteleadingbidHistory')->name('leadingbid.history.deleteone');
            Route::get('winningbid-history', 'UserController@winningbidHistory')->name('winningbid.history');
            Route::post('winningbid-history/delete', 'UserController@winningbiddelete')->name('winningbid.delete');
            Route::post('winningbid-history/update-combine', 'UserController@updatecombineshipping')->name('winningbid.updatecombineshipping');
            Route::post('winningbid-history/unmark-combine', 'UserController@unmarkcombineshipping')->name('winningbid.unmarkcombineshipping');
            Route::post('winningbid-history/update-pickupdate', 'UserController@updatepickupdate')->name('winningbid.updatepickupdate');
            Route::post('winningbid-history/unpark-pickupdate', 'UserController@unmarkpickupdate')->name('winningbid.unmarkpickupdate');
            Route::post('winningbid-history/update-checkout', 'UserController@updatecheckout')->name('winningbid.updatecheckout');
            Route::post('winningbid-history/update-uncheckout', 'UserController@unupdatecheckout')->name('winningbid.unupdatecheckout');

            Route::post('winning-history/add-shippingnew', 'UserController@addshippingnew')->name('winningbid.addshippingnew');

            Route::get('winningbid-history/search/{skeys}', 'UserController@searchwinningbidHistory')->name('searchwinningbid.history');
            Route::get('outbidden-history', 'UserController@outbiddenHistory')->name('outbidden.history');
            Route::get('bidding-history', 'UserController@biddingHistory')->name('bidding.history');
            Route::get('winning-history', 'UserController@winningHistory')->name('winning.history');
            Route::get('winning-history/search/{skeys}', 'UserController@searchwinningHistory')->name('searchwinning.history');
            Route::post('winning-history/deleteitem', 'UserController@winningHistorydelete')->name('winninghistory.delete');

            Route::post('winning-history/update-product-combine', 'UserController@productupdatecombineshipping')->name('winningbid.productupdatecombineshipping');
            Route::post('winning-history/unmark-product-combine', 'UserController@productunmarkcombineshipping')->name('winningbid.productunmarkcombineshipping');
            Route::post('winning-history/update-product-pickupdate', 'UserController@productupdatepickupdate')->name('winningbid.productupdatepickupdate');
            Route::post('winning-history/unpark-product-pickupdate', 'UserController@productunmarkpickupdate')->name('winningbid.productunmarkpickupdate');
            Route::post('winning-history/update-product-checkout', 'UserController@productupdatecheckout')->name('winningbid.productupdatecheckout');
            Route::post('winning-history/update-product-uncheckout', 'UserController@productunupdatecheckout')->name('winningbid.productunupdatecheckout');

            Route::get('transactions', 'UserController@transactions')->name('transactions');
            Route::get('transactions/search/{skeys}', 'UserController@searchtransactions')->name('searchtransactions');
            Route::post('search-item/save', 'UserController@searchitemsave')->name('searchitem.save');
            Route::get('searchlist/index', 'UserController@searchlistindex')->name('searchlist.index');
            Route::get('searchlist/delete/{id}', 'UserController@searchlistdelete')->name('searchlist.delete');

            Route::get('profile-setting', 'UserController@profile')->name('profile.setting');
            Route::post('profile-setting', 'UserController@submitProfile');
            Route::get('change-password', 'UserController@changePassword')->name('change.password');
            Route::post('change-password', 'UserController@submitPassword');

            Route::post('clear/answer', 'UserController@clearanswer')->name('clear.answer');

            Route::post('auction-question', 'AuctionController@auctionQuestionSave')->name('auction.question.save');
            Route::post('product-question', 'ProductController@productQuestionSave')->name('product.question.save');

            // Answers Notification
            Route::get('notification/answer', 'UserController@notificationAnswers')->name('notification.answers');
            Route::get('notification/answer/onedelete/{id}', 'UserController@notificationAnswerOneDelete')->name('notification.answers.onedelete');
            Route::get('notification/answerread-all','UserController@answerReadAll')->name('notification.answerreadAll');

            //Notification
            Route::get('notifications','UserController@notifications')->name('notifications');
            Route::get('notification/read/{id}','UserController@notificationRead')->name('notification.read');
            Route::get('notification/productread/{id}','UserController@notificationProductRead')->name('notification.productread');
            Route::get('notification/changestatusreact/{id}','UserController@notificationChangingStatusRead')->name('notification.changingstatusread');
            Route::get('notification/productchangestatusreact/{id}','UserController@notificationProductChangingStatusRead')->name('notification.productchangingstatusread');
            Route::get('notification/onedelete/{id}','UserController@notificationDelete')->name('notification.onedelete');
            Route::get('notifications/read-all','UserController@readAll')->name('notifications.readAll');
            Route::get('notifications/{id}/bids', 'UserController@notificationBids')->name('notifications.bids');

            //2FA
            Route::get('twofactor', 'UserController@show2faForm')->name('twofactor');
            Route::post('twofactor/enable', 'UserController@create2fa')->name('twofactor.enable');
            Route::post('twofactor/disable', 'UserController@disable2fa')->name('twofactor.disable');

            Route::get('closeaccount', 'UserController@closeAccount')->name('closeaccount');
            Route::get('closeaccount/close', 'UserController@closeAccountclose')->name('closeaccount.close');

            // Bonus Scheme
            Route::get('/bonus', 'UserController@bonus')->name('bonus');

            // Item Monitoring
            Route::get('/monitor', 'UserController@monitor')->name('monitor');

            // Deposit
            Route::any('/deposit', 'Gateway\PaymentController@deposit')->name('deposit');
            Route::post('deposit/insert', 'Gateway\PaymentController@depositInsert')->name('deposit.insert');
            Route::get('deposit/preview', 'Gateway\PaymentController@depositPreview')->name('deposit.preview');
            Route::get('deposit/confirm', 'Gateway\PaymentController@depositConfirm')->name('deposit.confirm');
            Route::get('deposit/manual', 'Gateway\PaymentController@manualDepositConfirm')->name('deposit.manual.confirm');
            Route::post('deposit/manual', 'Gateway\PaymentController@manualDepositUpdate')->name('deposit.manual.update');
            Route::any('deposit/history', 'UserController@depositHistory')->name('deposit.history');

            Route::post('bid', 'ProductController@bid')->name('bid');
            Route::post('product-review', 'ProductController@saveProductReview')->name('product.review.store');
            Route::post('merchant-review', 'ProductController@saveMerchantReview')->name('merchant.review.store');

            // Auction Controller
            Route::post('auction-bid', 'AuctionController@bid')->name('auctionbid');
            Route::post('auction-addbid', 'AuctionController@addbid')->name('auctionaddbid');
            Route::post('auction-review', 'AuctionController@saveAuctionReview')->name('auction.review.store');
            // End Auction Controller

            Route::post('shopping-cart/savewinner', "ShoppingController@savewinner")->name('shopping-cart.savewinner');

        });
    });
});

Route::get('products', 'ProductController@products')->name('product.all');

// auction
Route::get('auction', 'AuctionController@auctions')->name('auction.all');
Route::get('search-auctions/filter', 'AuctionController@filter')->name('auction.search.filter');
Route::get('auction-details/{id}/{slug}', 'AuctionController@auctionDetails')->name('auction.details');
Route::get('auction-reviews', 'AuctionController@loadMore')->name('auction.review.load');
// end auction

Route::get('search-products', 'SearchproductController@searchproducts')->name('product.search');
Route::get('search-listproducts', 'SearchproductController@searchlistproducts')->name('product.searchlist');
Route::get('category/{category_id}/{slug}', 'ProductController@products')->name('category.products');
Route::get('search-products/filter', 'ProductController@filter')->name('product.search.filter');
Route::get('search-allproducts/filter', 'SearchproductController@searchfilter')->name('product.asearch.filter');
Route::get('product-details/{id}/{slug}', 'ProductController@productDetails')->name('product.details');
Route::get('reviews', 'ProductController@loadMore')->name('product.review.load');

Route::get('auctions-live', 'SiteController@liveAuction')->name('live.auctions');
Route::get('auctions-upcoming', 'SiteController@upcomingAuction')->name('upcoming.auctions');
Route::get('products-live', 'SiteController@liveProduct')->name('live.products');
Route::get('products-upcoming', 'SiteController@upcomingProduct')->name('upcoming.products');
Route::get('categories', 'SiteController@categories')->name('categories');

Route::get('/adRedirect/{id}', 'SiteController@adRedirect')->name('adRedirect');


Route::get('merchants', 'SiteController@merchants')->name('merchants');
Route::get('admin-profile/{id}/{name}', 'SiteController@adminProfile')->name('admin.profile.view');
Route::get('merchant-profile/{id}/{name}', 'SiteController@merchantProfile')->name('merchant.profile.view');

Route::get('about-us', 'SiteController@aboutUs')->name('about.us');


Route::get('page/{id}/{slug}', 'SiteController@policy')->name('policy');

Route::get('/contact', 'SiteController@contact')->name('contact');
Route::get('/faq', 'SiteController@faq')->name('faq');
Route::post('/contact', 'SiteController@contactSubmit');
Route::get('/change/{lang?}', 'SiteController@changeLanguage')->name('lang');
Route::post('/germanchange', 'SiteController@germanchangeLanguage')->name('german.change.lang');

Route::get('/cookie/accept', 'SiteController@cookieAccept')->name('cookie.accept');

Route::get('/blogs', 'SiteController@blogs')->name('blog');
Route::get('blog/{id}/{slug}', 'SiteController@blogDetails')->name('blog.details');

Route::get('placeholder-image/{size}', 'SiteController@placeholderImage')->name('placeholder.image');


Route::get('/wishlist/{uid}/{cip}', "WishlistController@index")->name('user.wishlist');
Route::get('/wishlist/add/{pid}/{uid}/{cip}', "WishlistController@add")->name('user.wishlist.add');
Route::post('/wishlist/delete', "WishlistController@deleteitem")->name('user.wishlist.delete');
Route::get('/wishlists/onedelete/{id}', "WishlistController@deleteoneitem")->name('user.wishlist.onedelete');

Route::get('/wishlist/auctionadd/{aid}/{uid}/{cip}', "WishlistController@auctionadd")->name('user.auctionwishlist.add');
Route::post('/wishlist/auctiondelete', "WishlistController@auctiondeleteitem")->name('user.auctionwishlist.delete');
Route::get('/auctionwishlist/auctiononedelete/{id}', "WishlistController@auctiononedeleteitem")->name('user.auctionwishlist.onedelete');

Route::get('/shopping-cart/unadd', "ShoppingController@unadd")->name('shopping-cart.unadd');

Route::get('/auctionbid/unlogin', "ShoppingController@unlogin")->name('auctionbid.unlogin');

Route::get('/shopping-cart/{uid}/{cip}', "ShoppingController@index")->name('user.shopping-cart');
Route::get('/shopping-cart/add/{pid}/{uid}/{cip}/{wid}', "ShoppingController@add")->name('user.shopping-cart.add');
Route::get('/shopping-cart/productadd/{pid}/{uid}/{cip}', "ShoppingController@product_shopping_add")->name('user.shopping-cart.productadd');
Route::get('/shopping-cart/add/{pid}/{uid}/{cip}', "ShoppingController@shopadd")->name('user.shopping-cart.shopadd');
Route::post('/shopping-cart/delete', "ShoppingController@deleteitem")->name('user.shopping-cart.delete');

Route::get('/sellwithus', 'SiteController@sellwithus')->name('sellwithus');
Route::post('/sellwithus/send', 'SiteController@sellwithusSend')->name('sellwithus.send');
Route::post('/sellwithus/oneupload', 'SiteController@storeoneimg')->name('sellwithus.oneimageupload');

Route::get('/privatesales', 'SiteController@privatesales')->name('privatesales');
Route::post('/privatesales/send', 'SiteController@privatesalesSend')->name('privatesales.send');

Route::post('/contact/send', 'SiteController@contactsend')->name('contact.send');

Route::get('/charity', 'SiteController@charity')->name('charity');

Route::post('/updatecolor', 'SiteController@updatecolor')->name('setting.updatecolor');
Route::get('/{slug}', 'SiteController@pages')->name('pages');
Route::get('/', 'SiteController@index')->name('home');
