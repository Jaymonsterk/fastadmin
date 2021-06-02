define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'order/order_user_yuebao/index' + location.search,
                    add_url: 'order/order_user_yuebao/add',
                    edit_url: 'order/order_user_yuebao/edit',
                    del_url: 'order/order_user_yuebao/del',
                    multi_url: 'order/order_user_yuebao/multi',
                    //import_url: 'order/order_user_yuebao/import',
                    table: 'order_user_yuebao',
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
                        // {checkbox: true},
                        {field: 'id', title: __('Id'), visible: false,operate: false},
                        {field: 'uid', title: __('Uid'), operate: false},
                        {field: 'uname', title: __('Uname'), operate: 'LIKE'},
                        {field: 'orderid', title: __('Orderid'), operate: 'LIKE'},
                        // {field: 'yuebaoid', title: __('Yuebaoid')},
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        // {field: 'day', title: __('Day'), operate: 'LIKE'},
                        {field: 'minmoney', title: __('Minmoney'), operate: false},
                        {field: 'proportion', title: __('Proportion'), operate: false},
                        // {field: 'dayproportion', title: __('Dayproportion'), operate: false},
                        {field: 'mindayproportion', title: __('Mindayproportion'), operate: false},
                        {field: 'setmoney', title: __('Setmoney'), operate:'BETWEEN'},
                        {field: 'willgetmoney', title: __('Willgetmoney'), operate:false},
                        {field: 'getmoney', title: __('Getmoney'), operate:false},
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2'),"3":__('Status 3')}, formatter: Table.api.formatter.status},
                        {field: 'ctime', title: __('Ctime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        // {field: 'cdate', title: __('Cdate'), operate: 'LIKE'},
                        {field: 'etime', title: __('Etime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        // {field: 'edate', title: __('Edate'), operate: 'LIKE'},
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});