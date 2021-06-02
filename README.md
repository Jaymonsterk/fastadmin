# 生成crud及菜单

### 1 用户管理
- User 用户管理
- UserBank 提现银行卡管理
- UserMessage 群发用户消息管理
- UserDeposit 用户充值管理
- UserWithdrawal 用户提现管理
  php think crud -t user -c user/User -u 1
  php think crud -t user_bank -c user/UserBank -u 1
  php think crud -t user_message -c user/UserMessage -u 1
  php think crud -t user_deposit -c user/UserDeposit -u 1
  php think crud -t user_withdrawal -c user/UserWithdrawal -u 1

### 2 订单管理
- OrderUserVip 用户等级管理
- OrderUserShop 用户任务订单管理
- OrderUserSignin 签到活动列表
- OrderUserYqzd 邀请做单奖励列表
- OrderUserYqcz 邀请充值奖励列表
- OrderUserOther 注册活动列表
- OrderUserYuebao 余额宝活动列表  
  php think crud -t order_user_vip -c order/OrderUserVip -u 1  
  php think crud -t order_user_shop -c order/OrderUserShop -u 1  
  php think crud -t order_user_signin -c order/OrderUserSignin -u 1  
  php think crud -t order_user_yqzd -c order/OrderUserYqzd -u 1  
  php think crud -t order_user_yqcz -c order/OrderUserYqcz -u 1  
  php think crud -t order_user_other -c order/OrderUserOther -u 1  
  php think crud -t order_user_yuebao -c order/OrderUserYuebao -u 1

### 3 配置管理
- ConfigCarousel 首页轮播配置
- ConfigActivity 活动banner图配置
- ConfigVip 用户等级配置
- UserDepositSf 三方充值配置
- ConfigShop 任务商品配置
- ConfigSignin 签到活动配置
- ConfigYqzd 邀请做单配置
- ConfigYqcz 邀请充值奖励配置
- ConfigSys 注册送+下级返佣活动配置
- ConfigYuebao 余额宝配置

  php think crud -t config_carousel -c config/ConfigCarousel -u 1  
  php think crud -t config_activity -c config/ConfigActivity -u 1  
  php think crud -t config_vip -c config/ConfigVip -u 1  
  php think crud -t user_deposit_sf -c config/UserDepositSf -u 1  
  php think crud -t config_shop -c config/ConfigShop -u 1  
  php think crud -t config_signin -c config/ConfigSignin -u 1  
  php think crud -t config_yqzd -c config/ConfigYqzd -u 1  
  php think crud -t config_yqcz -c config/ConfigYqcz -u 1  
  php think crud -t config_sys -c config/ConfigSys -u 1  
  php think crud -t config_yuebao -c config/ConfigYuebao -u 1


### 4 报表管理
- ReportUserDay 数据总览
- ReportAgencyDay 用户结构报表
- ReportUserDay 用户充值日报表 提现 任务
  php think crud -t report_user_day -c report/ReportUserDay -u 1
  php think crud -t report_agency_day -c report/ReportAgencyDay -u 1

### 5 日志管理
Funds   资金流水日志列表
OpertationLog 用户操作日志
后台操作日志
php think crud -t user_money_log -c log/MoneyLog -u 1

### 更新SQL

ALTER TABLE `dianzan_v2`.`user`
MODIFY COLUMN `isfistdeposit` int NOT NULL DEFAULT 0 COMMENT '是否完成首冲:1=是,0=否' AFTER `historyrechargelmoney`,
MODIFY COLUMN `status` int NOT NULL DEFAULT 1 COMMENT '状态:1=正常,2=停用(不可登录),3=可登录停止提现' AFTER `vmonadmun`;
ALTER TABLE `dianzan_v2`.`user`
MODIFY COLUMN `isfistdeposit` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否完成首冲:1=是,0=否' AFTER `historyrechargelmoney`,
MODIFY COLUMN `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '状态:1=正常,2=停用(不可登录),3=可登录停止提现' AFTER `vmonadmun`;
ALTER TABLE `dianzan_v2`.`user`
MODIFY COLUMN `isfistdeposit` int NOT NULL DEFAULT 0 COMMENT '是否完成首冲:1=是,0=否' AFTER `historyrechargelmoney`,
MODIFY COLUMN `status` int NOT NULL DEFAULT 1 COMMENT '状态:1=正常,2=停用(不可登录),3=可登录停止提现' AFTER `vmonadmun`;

ALTER TABLE `dianzan`.`order_user_shop`
MODIFY COLUMN `status` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '状态:1=进行中,2=成功,3=失败' AFTER `creditscore`;
ALTER TABLE `dianzan`.`order_user_shop`
MODIFY COLUMN `status` enum('1','2','3') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态:1=进行中,2=成功,3=失败' AFTER `creditscore`;
ALTER TABLE `dianzan`.`order_user_shop`
MODIFY COLUMN `status` int(4) NOT NULL DEFAULT 1 COMMENT '状态:1=进行中,2=成功,3=失败' AFTER `creditscore`;

ALTER TABLE `dianzan_v2`.`order_user_signin`
MODIFY COLUMN `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '状态:1=已使用,2=未使用' AFTER `reward`;
ALTER TABLE `dianzan_v2`.`order_user_signin`
MODIFY COLUMN `status` int NOT NULL DEFAULT 1 COMMENT '状态:1=已使用,2=未使用' AFTER `reward`;

ALTER TABLE `dianzan_v2`.`order_user_yuebao`
MODIFY COLUMN `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '状态:1=进行中,2=成功,3=停止' AFTER `cdate`;

ALTER TABLE `dianzan_v2`.`config_activity`
MODIFY COLUMN `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '状态:1=开启,2=关闭' AFTER `sort`;
