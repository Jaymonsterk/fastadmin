echo "init starting..."
  php think crud -t user -c user/User -u 1 --force=true
  php think crud -t user_bank -c user/UserBank -u 1 --force=true
  php think crud -t user_message -c user/UserMessage -u 1 --force=true
  php think crud -t user_deposit -c user/UserDeposit -u 1 --force=true
  php think crud -t user_withdrawal -c user/UserWithdrawal -u 1 --force=true


  php think crud -t order_user_vip -c order/OrderUserVip -u 1 --force=true
  php think crud -t order_user_shop -c order/OrderUserShop -u 1 --force=true
  php think crud -t order_user_signin -c order/OrderUserSignin -u 1 --force=true
  php think crud -t order_user_yqzd -c order/OrderUserYqzd -u 1 --force=true
  php think crud -t order_user_yqcz -c order/OrderUserYqcz -u 1 --force=true
  php think crud -t order_user_other -c order/OrderUserOther -u 1 --force=true
  php think crud -t order_user_yuebao -c order/OrderUserYuebao -u 1 --force=true


  php think crud -t config_carousel -c config/ConfigCarousel -u 1 --fields=id,title,img,jumpurl,sort,note --imagefield=img --force=true
  php think crud -t config_activity -c config/ConfigActivity -u 1 --fields=id,title,img,jumpurl,sort,status,note --imagefield=img --force=true
  php think crud -t config_vip -c config/ConfigVip -u 1 --force=true
  php think crud -t user_deposit_sf -c config/UserDepositSf -u 1 --force=true
  php think crud -t config_shop -c config/ConfigShop -u 1 --force=true
  php think crud -t config_signin -c config/ConfigSignin -u 1 --force=true
  php think crud -t config_yqzd -c config/ConfigYqzd -u 1 --force=true
  php think crud -t config_yqcz -c config/ConfigYqcz -u 1 --force=true
  php think crud -t config_sys -c config/ConfigSys -u 1 --force=true
  php think crud -t config_yuebao -c config/ConfigYuebao -u 1 --force=true

  php think crud -t report_user_day -c report/ReportUserDay -u 1 --force=true
  php think crud -t report_agency_day -c report/ReportAgencyDay -u 1 --force=true

  php think crud -t user_money_log -c log/MoneyLog -u 1 --force=true
  php think crud -t user_operation_log -c log/UserOperationLog -u 1 --force=true