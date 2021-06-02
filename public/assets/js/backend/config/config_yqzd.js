define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'config/config_yqzd/index' + location.search,
                    add_url: 'config/config_yqzd/add',
                    edit_url: 'config/config_yqzd/edit',
                    del_url: 'config/config_yqzd/del',
                    multi_url: 'config/config_yqzd/multi',
                    //import_url: 'config/config_yqzd/import',
                    table: 'config_yqzd',
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
                        {field: 'ynum', title: __('Ynum'), operate: 'LIKE'},
                        {field: 'yname', title: __('Yname'), operate: 'LIKE'},
                        {field: 'mun', title: __('Mun'), operate: 'LIKE'},
                        {field: 'money', title: __('Money'), operate: 'LIKE'},
                        {field: 'reward', title: __('Reward'), operate: 'LIKE'},
                        {field: 'creditscore', title: __('Creditscore'), operate: 'LIKE'},
                        {field: 'note', title: __('Note'), operate: 'LIKE'},
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