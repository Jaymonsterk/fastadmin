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
                        {field: 'id', title: __('Id'),operate: false},
                        {field: 'uid', title: __('Uid'),operate: false},
                        {field: 'uname', title: __('Uname'), operate: 'LIKE'},
                        {field: 'bid', title: __('Bid')},
                        {field: 'bname', title: __('Bname'), visible:false, operate: false},
                        {field: 'orderid', title: __('Orderid'), operate: 'LIKE'},
                        {field: 'money', title: __('Money'), operate:'BETWEEN'},
                        {field: 'fee', title: __('Fee'), operate:false},
                        {field: 'totalmoney', title: __('Totalmoney'), operate:false},
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2'),"3":__('Status 3'),"4":__('Status 4')}, formatter: Table.api.formatter.status},
                        {field: 'note', title: __('Note'), operate: 'LIKE'},
                        {field: 'ctime', title: __('Ctime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'aid', title: __('Aid'),visible:false,operate: false},
                        {field: 'aname', title: __('Aname'),visible:false,operate: false},
                        {field: 'utime', title: __('Utime'),visible:false,operate: false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'process',
                                    title: __('Process'),
                                    text: __('Process'),
                                    classname: 'btn btn-xs btn-info btn-ajax',
                                    icon: 'fa fa-flash',
                                    url: function (row, j) {
                                        return 'user/user_withdrawal/multi/ids/'+row.id+'/params/status=2';
                                    },
                                    refresh:true,
                                    visible:function (row, j) {
                                        return row.status == 1;
                                    },
                                    hidden:function (row, j) {
                                        return row.status > 1;
                                    }
                                },
                                {
                                    name: 'success',
                                    title: __('Success'),
                                    text: __('Success'),
                                    classname: 'btn btn-xs btn-success btn-ajax',
                                    icon: 'fa fa-handshake-o',
                                    url: function (row, j) {
                                        return 'user/user_withdrawal/multi/ids/'+row.id+'/params/status=3';
                                    },
                                    refresh:true,
                                    visible:function (row, j) {
                                        return row.status == 1 || row.status == 2;
                                    },
                                    hidden:function (row, j) {
                                        return row.status > 2;
                                    }
                                },
                                {
                                    name: 'failure',
                                    title: __('Failure'),
                                    text: __('Failure'),
                                    classname: 'btn btn-xs btn-danger btn-ajax',
                                    icon: 'fa fa-arrow-circle-o-up',
                                    url: function (row, j) {
                                        return 'user/user_withdrawal/multi/ids/'+row.id+'/params/status=4';
                                    },
                                    refresh:true,
                                    visible:function (row, j) {
                                        return row.status == 1 || row.status == 2;
                                    },
                                    //如果没有绑定过谷歌验证码则不显示
                                    hidden:function (row, j) {
                                        return row.status > 2;
                                    }
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
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});