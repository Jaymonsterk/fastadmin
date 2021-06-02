define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'config/user_deposit_sf/index' + location.search,
                    add_url: 'config/user_deposit_sf/add',
                    edit_url: 'config/user_deposit_sf/edit',
                    del_url: 'config/user_deposit_sf/del',
                    multi_url: 'config/user_deposit_sf/multi',
                    //import_url: 'config/user_deposit_sf/import',
                    table: 'user_deposit_sf',
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
                        {field: 'id', title: __('Id'),visible:false,operate: false},
                        {field: 'name', title: __('Name'), operate: 'LIKE'},
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2')}, formatter: Table.api.formatter.status},
                        {field: 'sort', title: __('Sort'), operate: false},
                        {field: 'showmoney', title: __('Showmoney'), operate: false},
                        {field: 'ds_notify_url', title: __('Ds_notify_url'), operate: false, formatter: Table.api.formatter.url},
                        {field: 'df_notify_url', title: __('Df_notify_url'), operate: false, formatter: Table.api.formatter.url},
                        {field: 'note', title: __('Note'), operate: 'LIKE'},
                        {field: 'aid', title: __('Aid'),visible:false,operate: false},
                        {field: 'aname', title: __('Aname'),visible:false,operate: false},
                        {field: 'utime', title: __('Utime'),visible:false,operate: false, addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
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