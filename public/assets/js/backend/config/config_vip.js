define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'config/config_vip/index' + location.search,
                    add_url: 'config/config_vip/add',
                    edit_url: 'config/config_vip/edit',
                    del_url: 'config/config_vip/del',
                    multi_url: 'config/config_vip/multi',
                    //import_url: 'config/config_vip/import',
                    table: 'config_vip',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'vnum',
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
                searchFormVisible: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'),visible:false,operate: false},
                        {field: 'vnum', title: __('Vnum'), operate: 'LIKE'},
                        {field: 'vname', title: __('Vname'), operate: 'LIKE'},
                        {field: 'taskmoney', title: __('Taskmoney'), operate: 'LIKE'},
                        {field: 'paymoney', title: __('Paymoney'), operate: 'LIKE'},
                        {field: 'vmonadmun', title: __('Vmonadmun'), operate: 'LIKE'},
                        {field: 'creditscore', title: __('Creditscore'), operate: 'LIKE'},
                        {field: 'note', title: __('Note'), operate: 'LIKE'},
                        {field: 'aid', title: __('Aid'),visible:false,operate: false},
                        {field: 'aname', title: __('Aname'), operate: 'LIKE',visible:false,operate: false},
                        {field: 'utime', title: __('Utime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime,visible:false,operate: false},
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