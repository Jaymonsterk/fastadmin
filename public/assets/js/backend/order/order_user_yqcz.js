define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'order/order_user_yqcz/index' + location.search,
                    // add_url: 'order/order_user_yqcz/add',
                    // edit_url: 'order/order_user_yqcz/edit',
                    // del_url: 'order/order_user_yqcz/del',
                    // multi_url: 'order/order_user_yqcz/multi',
                    //import_url: 'order/order_user_yqcz/import',
                    table: 'order_user_yqcz',
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
                        {field: 'id', title: __('Id'), visible: false,operate: false},
                        {field: 'uid', title: __('Uid'), operate: false},
                        {field: 'uname', title: __('Uname'), operate: 'LIKE'},
                        {field: 'orderid', title: __('Orderid'), operate: 'LIKE'},
                        // {field: 'yqczid', title: __('Yqczid')},
                        // {field: 'ynum', title: __('Ynum'), operate: 'LIKE'},
                        {field: 'yname', title: __('Yname'), operate: 'LIKE'},
                        {field: 'money', title: __('Money'), operate: 'LIKE'},
                        {field: 'ctime', title: __('Ctime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        // {field: 'cdate', title: __('Cdate'), operate: 'LIKE'},
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