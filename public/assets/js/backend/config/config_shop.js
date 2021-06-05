define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'config/config_shop/index' + location.search,
                    add_url: 'config/config_shop/add',
                    edit_url: 'config/config_shop/edit',
                    del_url: 'config/config_shop/del',
                    multi_url: 'config/config_shop/multi',
                    //import_url: 'config/config_shop/import',
                    table: 'config_shop',
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
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        {field: 'usemoney', title: __('Usemoney'),sortable:true, operate: false},
                        {field: 'reward', title: __('Reward'), operate: false},
                        {field: 'totalnum', title: __('Totalnum'), operate: false},
                        {field: 'usenum', title: __('Usenum'), operate: false},
                        {field: 'image', title: __('Image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'vnum', title: __('Vnum'), searchList:Config.vip_list, formatter: Table.api.formatter.normal},
                        {field: 'usetime', title: __('Usetime'), operate: false},
                        {field: 'creditscore', title: __('Creditscore'), operate: false},
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2')}, yes:1, no:2, formatter: Table.api.formatter.toggle},
                        {field: 'sort', title: __('Sort'), operate: false},
                        {field: 'note', title: __('Note'), operate: 'LIKE'},
                        // {field: 'aid', title: __('Aid')},
                        // {field: 'aname', title: __('Aname'), operate: 'LIKE'},
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
                // 给上传按钮添加上传成功事件
                $("#faupload-image").data("upload-success", function (data) {
                    var url = Backend.api.cdnurl(data.url, Config.upload.cdnurl);
                    $("#c-image").val(url);
                    console.log(url);
                    Toastr.success("上传成功！");
                });

                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});