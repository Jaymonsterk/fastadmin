define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'user/user/index' + location.search,
                    add_url: 'user/user/add',
                    edit_url: 'user/user/edit',
                    //del_url: 'user/user/del',
                    multi_url: 'user/user/multi',
                    //import_url: 'user/user/import',
                    table: 'user',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                //切换卡片视图和表格视图两种模式
                showToggle:false,
                //显示隐藏列可以快速切换字段列的显示和隐藏
                showColumns:true,
                //导出整个表的所有行导出整个表的所有行
                showExport:false,
                //搜索
                search: false,
                //搜索功能，
                commonSearch: true,
                //表格上方的搜索搜索指表格上方的搜索
                searchFormVisible: true,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'areacode', title: __('Areacode'), operate: 'LIKE'},
                        {field: 'uname', title: __('Uname'), operate: 'LIKE'},
                        {field: 'nname', title: __('Nname'),visible:false, operate: false},
                        //{field: 'passwd', title: __('Passwd'), operate: 'LIKE'},
                        //{field: 'paypasswd', title: __('Paypasswd'), operate: 'LIKE'},
                        {field: 'mobile', title: __('Mobile'), visible:false, operate: false},
                        //{field: 'email', title: __('Email'), operate: 'LIKE'},
                        {field: 'creditscore', title: __('Creditscore'), sortable:true,operate: 'LIKE'},
                        {field: 'money', title: __('Money'),sortable:true, operate:'BETWEEN'},
                        {field: 'yebmoney', title: __('Yebmoney'),sortable:true, operate:'BETWEEN'},
                        {field: 'historyrechargelmoney', title: __('Historyrechargelmoney'),sortable:true, operate:'BETWEEN'},
                        {field: 'isfistdeposit', title: __('Isfistdeposit'), searchList: {"0":__('Isfistdeposit 1'),"1":__('Isfistdeposit 2')}, formatter: Table.api.formatter.normal},
                        {field: 'vnum', title: __('Vnum'), sortable:true,searchList: Config.vip_list, formatter: Table.api.formatter.normal},
                        //{field: 'vname', title: __('Vname'), operate: 'LIKE'},
                        {field: 'vmonadmun', title: __('Vmonadmun'),sortable:true, operate: false},
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2'),"3":__('Status 3')}, formatter: Table.api.formatter.status},
                        {field: 'cip', title: __('Cip'), visible:false, operate: false},
                        {field: 'ctime', title: __('Ctime'), sortable:true,operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'cdate', title: __('Cdate'), visible:false, operate: false},
                        {field: 'lip', title: __('Lip'), visible:false, operate: false},
                        {field: 'ltime', title: __('Ltime'), sortable:true,operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'ldate', title: __('Ldate'), visible:false, operate: false},
                        {field: 'aid', title: __('Aid'), visible:false, operate: false},
                        {field: 'aname', title: __('Aname'), visible:false, operate: false},
                        {field: 'utime', title: __('Utime'), visible:false, operate: false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                            buttons:[
                                {
                                    name: 'recharge',
                                    text: __('Manual Recharge'),
                                    classname: 'btn btn-xs btn-danger btn-dialog',
                                    icon: 'fa fa-list',
                                    url: function (row) {
                                        return 'user/user/recharge/ids/'+row.id;
                                    },
                                    callback: function (data) {
                                        //回调方法，用来响应 Fast.api.close()方法 **注意不能有success 是btn-ajax的回调，btn-dialog 用的callback回调，两者不能同存！！！！
                                        //$(".btn-refresh").trigger("click");//刷新当前页面的数据
                                        Layer.alert("recharge：" + data.msg);
                                        //console.error(data);//控制输出回调数据
                                    },
                                },
                            ]
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        recharge: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});