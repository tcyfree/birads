define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'mall.goods/index',
        add_url: 'mall.goods/add',
        edit_url: 'mall.goods/edit',
        delete_url: 'mall.goods/delete',
        export_url: 'mall.goods/export',
        modify_url: 'mall.goods/modify',
        stock_url: 'mall.goods/stock',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar: ['refresh', 'add', 'delete'],
                limit:15,
                limits:[5,10,15,20],
                cols: [[
                    {type: "checkbox"},
                    // {field: 'id', width: 80, title: 'ID'},
                    {field: 'title', minWidth: 180, title: '病人ID'},
                    {field: 'logo', title: '第一次分类', minWidth: 120, selectList: {0: '', 1: '良', 2: '恶'}},
                    {field: 'sales', title: '第二次分类', minWidth: 120, selectList: {0: '', 1: '良', 2: '恶'}},
                    {field: 'stock', title: '地区', minWidth: 120, selectList: {0: 'dz', 1: 'ts', 2: 'sw'}},
                    // {field: 'users.username', title: '医护人员'},
                    {field: 'status', title: '是否完成', minWidth: 100, selectList: {1: '否', 2: '是'}},
                    {field: 'remark', minWidth: 180, title: '备注信息'},
                    {
                        minWidth: 100,
                        title: '操作',
                        templet: ea.table.tool,
                        operat: [
                            [{
                                text: 'Detect',
                                url: init.edit_url,
                                method: 'open',
                                auth: 'edit',
                                class: 'layui-btn layui-btn-xs layui-btn-success',
                                extend: 'data-full="true"',
                            }],
                            ]
                    }
                ]],
            });

            ea.listen();
        },
        add: function () {
            ea.listen();
        },
        edit: function () {
            ea.listen();
        },
        stock: function () {
            ea.listen();
        },
    };
    return Controller;
});