define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'user/user_withdrawal/index' + location.search,
                    add_url: 'user/user_withdrawal/add',
                    edit_url: 'user/user_withdrawal/edit',
                    del_url: 'user/user_withdrawal/del',
                    multi_url: 'user/user_withdrawal/multi',
                    //import_url: 'user/user_withdrawal/import',
                    table: 'user_withdrawal',
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
                        {field: 'uid', title: __('Uid')},
                        {field: 'uname', title: __('Uname'), operate: 'LIKE'},
                        {field: 'bid', title: __('Bid')},
                        {field: 'bname', title: __('Bname'), operate: 'LIKE'},
                        {field: 'orderid', title: __('Orderid'), operate: 'LIKE'},
                        {field: 'money', title: __('Money'), operate:'BETWEEN'},
                        {field: 'fee', title: __('Fee'), operate:'BETWEEN'},
                        {field: 'totalmoney', title: __('Totalmoney'), operate:'BETWEEN'},
                        {field: 'status', title: __('Status')},
                        {field: 'note', title: __('Note'), operate: 'LIKE'},
                        {field: 'ctime', title: __('Ctime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'aid', title: __('Aid')},
                        {field: 'aname', title: __('Aname'), operate: 'LIKE'},
                        {field: 'utime', title: __('Utime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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