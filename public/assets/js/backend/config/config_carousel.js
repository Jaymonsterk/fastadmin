define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'config/config_carousel/index' + location.search,
                    add_url: 'config/config_carousel/add',
                    edit_url: 'config/config_carousel/edit',
                    del_url: 'config/config_carousel/del',
                    multi_url: 'config/config_carousel/multi',
                    //import_url: 'config/config_carousel/import',
                    table: 'config_carousel',
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
                        {field: 'img', title: __('Img'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'jumpurl', title: __('Jumpurl'), operate: 'LIKE', formatter: Table.api.formatter.url},
                        {field: 'sort', title: __('Sort'), sortable:true, operate: 'LIKE',operate: false},
                        {field: 'note', title: __('Note'), operate: 'LIKE'},
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
                $("#faupload-img").data("upload-success", function (data) {
                    var url = Backend.api.cdnurl(data.url, Config.upload.cdnurl);
                    $("#c-img").val(url);
                    console.log(url);
                    Toastr.success("上传成功！");
                });

                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});