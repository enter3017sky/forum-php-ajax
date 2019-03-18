$(document).ready(function() {
    /** 
    原本事件掛在 $('.wrapper__form') 但是這個元素本身也是動態新增的，所以新增的留言 submit edit delete 都無法執行。
    解法1：掛高高，掛在動態新增以外的那層
    解法2： https://stackoverflow.com/questions/8408826/bind-event-only-once

    */

    // 新增留言
    $('.container').on('click', 'input.submit__btn', function(e) {
        e.preventDefault(); // 阻止 submit
        var $eT = $(e.target) // 取得 jquery e 的點擊
        var getContent = $eT.parents('form.meg__form').find('textarea.content').val() // 取得 textarea 內容文字
        var clearContent = $eT.parents('form.meg__form').find('textarea.content').val('') // 清光 textarea 內容文字
        var parentId = $eT.parents('form.meg__form').find('input[name=parent_id]').val() // 取得 parent_id
        var showNickname = $eT.parents('form.meg__form').find('input[name=nickname]').attr('value') // 取得使用者暱稱
        // 判斷是哪個 submit, 0=> 新增主留言, 1=>新增子留言

        var checkUser = $eT.parents('.comments__wrapper').find('.nick').text().trim()
        const checkSubSubmit = $eT.parents('.comments__wrapper').length

        console.log('parentId ',parentId, 'getContent', getContent)


        $.ajax({
            method: 'POST',
            url: './add_comment.php',
            data: {
                parent_id: parentId,
                content: getContent
            }

        }).done(function(response) {
            var resp = JSON.parse(response);
            var id = resp.id

            if(resp.result === 'Success') { // server 成功才執行
                if(parentId === '0' && !checkSubSubmit) { // 檢查 id 及 判斷 submit
                    clearContent
                    $eT.parents('.wrapper__form').after(createMainComment(showNickname, getContent, id)) // 產生留言框
                    $('.wrapper__form + .comments__wrapper').hide().show(500) // 取得產生的留言框，先隱藏在秀出

                    console.log('新增 主留言 成功')
                } else if(showNickname === checkUser) { // 判斷主留言與子留言是否使用者相同
                    clearContent
                    // 點擊提交時找到最上層的 .wrapper__form，的前面(.before)的前一個兄弟元素(.prev())建立留言
                    $eT.parents('.wrapper__form').before(createSubComment(showNickname, getContent, id)).prev().hide().show(500)
                    console.log('新增 在自己主留言底下新增子留言')
                } else {
                    clearContent
                    $eT.parents('.wrapper__form').before(createSubComment(showNickname, getContent, id, false)).prev().hide().show(500)
                }
            } else {
                return
            }
        }).fail(function() {
            alert('add msg error')
        })
    });

    // 編輯留言
    $('.container').on('click', 'input.edit__btn', function(e) {
        const $eT = $(e.target)
        const editId = $eT.attr('data-id') // 取得 id
        const checkEditing = $eT.parents('.container').find('.editing').length // 尋找 .editing 以判斷是否正在編輯
        const checkSubEdit = $eT.parents('.sub-meg__user') // 子留言最上層
        const checkMainEdit = $eT.parent().parent().parent().parent('.meg__wrap') // 判斷主留言
        const editSubText = $eT.parents('.sub-meg__user').contents('.sub-comment__content') // 取得子留言的內容
        const editText = $eT.parents('.meg__wrap').contents('.comment__content') // 取得主留言的內容
        const createMsgTarget = $eT.parents('.meg__wrap').contents('.comment__header')  // 建立主留言的位置定位
        var checkOnlyMsg = $eT.parents('.meg__wrap').contents('.sub-meg__user').length // 判斷是否只有主留言

        if(!checkEditing) {  // 檢查是否正在編輯
            if(checkSubEdit.length) { // 子留言編輯
                $eT.addClass('alert-success')
                content = editSubText.text().trim()
                editSubText.hide(400, function() {
                    $(this).remove();
                })
                editSubBox(checkSubEdit, content, editId)
                console.log('編輯 子留言')
            } else if(!checkSubEdit.length && checkMainEdit) {
                if(!checkOnlyMsg) { // 0 的話是只有單一主留言的編輯
                    $eT.parents('.meg__wrap').find('.wrapper__form').hide(350)
                    content = editText.text().trim();
                    editText.hide(350, function() {
                        $(this).remove();
                    })
                    editMainBox(createMsgTarget, content, editId)
                    console.log('編輯 單一主留言')
                } else {
                    content = editText.text().trim()
                    editText.hide(400, function() {
                        $(this).remove();
                    })
                    editMainBox(createMsgTarget, content, editId);
                    console.log('編輯 主留言')
                }
            };
        } else {
            return
        }
    });

    // 送出編輯完成的留言
    $('.container').on('click', '.edit__submit', function(e) {
        e.preventDefault()
        const $eT = $(e.target)
        const getId = $eT.parents('.comments__wrapper').find('.edit__divBtn > input[type=hidden]').val() // 取得編輯者的 id
        const content = $eT.parents('.comments__wrapper').find('.editDone').val() // 取得編輯的文字
        const cloneText = $eT.parents('.comments__wrapper').find('.editDone').text() // 複製文字
        const checkOnlyMsg = $eT.parents('.meg__wrap').find('.wrapper__form:hidden') // 取得只有主留言而隱藏的留言框
        const checkSubEdit = $eT.parents('.sub-meg__user').parents('.meg__wrap').length // 判斷是否為子留言的編輯

        if(content) { // 如果留言不為空
            $.ajax({
                method: 'POST',
                url: './handle_edit_comment.php',
                data: {
                    id: getId,
                    content
                }
            }).done(function(response) {
                var res = JSON.parse(response)
                alert(res.message)
                    // 本來兩個 if 再一起，應該以流程為主，成功才執行動畫，這樣動態效果才好
                if(checkSubEdit) { // 判斷是否為子留言的編輯
                    editDoneAndSubmit($eT, content, 0)
                    console.log('編輯完成 子留言')
                } else if(checkOnlyMsg.length) { // 單一主留言的情況
                    checkOnlyMsg.show(350) // 單主留言：秀出隱藏起來留言區塊
                    editDoneAndSubmit($eT, content)
                    console.log('編輯完成 單一主留言')
                } else { // 主留言底下有子留言的情況
                    editDoneAndSubmit($eT, content)
                    console.log('編輯完成 主留言')
                }
            }).fail(function(response) {
                var res = JSON.parse(response)
                alert(res.message)
            })

        } else {
            alert('更新留言不能為空!')
            // 如果留言為空，恢復原本的留言
            $eT.parents('.editing').find('.editDone').val(cloneText)
            console.log('編輯完成 但是內容為空')
        }
    })

        // 刪除留言
    $('.container').on('click', 'input.delete__btn', function(e) {
        e.preventDefault();
        var $eT = $(e.target);
        var id = $eT.attr('data-id') // 取得匹配的元素集合有 data-id 屬性的值
        var checkSubDel = $eT.parents('.sub-meg__user').length // 判斷留言
        if(!confirm('確定刪除訊息？')) return; // confirm 跳出確認刪除框
        $.ajax({
            method: 'POST',
            url: './delete_comment.php',
            // data 要以物件的方式傳送  (data: id 是錯誤的, 有傳到 server 但內容怪怪的刪除失敗)
            data: {
                id
            }
        }).done(function(response) {
            var resp = JSON.parse(response)
            alert(resp.message)
            if(checkSubDel) { // 判斷是否是子留言
                $eT.parents('.sub-meg__user')
                    .hide(350)
                    .end()
                    .remove()
                console.log('刪除 子留言')
            } else {
                $eT.parents('.comments__wrapper')
                    .hide(350)
                    .end()
                    .remove()
                console.log('刪除 主留言')
            }
            
        }).fail(function() {
            alert('刪除失敗')
        })
    })
        // 新增主留言
    function createMainComment(showNickname, getContent, id) {
        return `
    <div class='comments__wrapper'>
        <div class="meg__wrap card border-dark mb-3"> 
            <div class="comment__header card-header">
                <div class="comment__header__left">
                    <div class="comment__author nick">${showNickname}</div> 
                    <div class="comment__timestamp">${showTime()}</div>
                </div>
                <div class="comment__header__right">
                    <div class='btn-group' role='group'>
                        <input type='button' class='delete__btn btn btn-outline-danger' data-id='${id}' value='刪除' />
                        <input type='button' class='edit__btn btn btn-outline-success' data-id='${id}' value='編輯'/>
                    </div>
                </div>
            </div>
            <div class="comment__content ">
                <p>
                    ${getContent}
                </p>
            </div>
                <div class="wrapper__form rounded-bottom w-100">
                    <form class="meg__form createSubMsg" method="POST" action="./add_comment.php">
                        <input type="hidden" class="hidden" name="parent_id" value="${id}">
                        <input type="hidden" class="hidden" name="nickname" value="${showNickname}" />
                        <div class='form-row'>
                            <textarea class="content form-control" name='content' type='textarea' placeholder="留言內容"></textarea>
                        </div>
                        <div class='form-row'>
                            <div class="sub__btn">
                                <input type="submit" class="submit__btn btn btn-primary">
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>`;
    }

        // 新增子留言
    function createSubComment(showNickname, getContent, id, flag = true) {
        if(flag) {
            return `
                <div class="sub-meg__user alert alert-light mb-2"> 
                    <div class="comment__header card-header">
                        <div class="comment__header__left">
                            <div class="sub-comment__author">${showNickname}</div> 
                            <div class="sub-comment__timestamp">${showTime()}</div>
                        </div>
                        <div class="comment__header__right">
                            <div class='btn-group' role='group'>
                                <input type='button' class='delete__btn btn btn-outline-danger' data-id='${id}' value='刪除' />
                                <input type='button' class='edit__btn btn btn-outline-success' data-id='${id}' value='編輯'/>
                            </div>
                        </div>
                    </div>
                    <div class="sub-comment__content card-body">
                        <p>
                            ${getContent}
                        </p>
                    </div>
                </div>`;
        } else {
            return `
            <div class="sub-meg__wrap sub-meg__user card border-dark mb-2"> 
                <div class="comment__header card-header">
                    <div class="comment__header__left">
                        <div class="sub-comment__author">${showNickname}</div> 
                        <div class="sub-comment__timestamp">${showTime()}</div>
                    </div>
                    <div class="comment__header__right">
                        <div class='btn-group' role='group'>
                            <input type='button' class='delete__btn btn btn-outline-danger' data-id='${id}' value='刪除' />
                            <input type='button' class='edit__btn btn btn-outline-success' data-id='${id}' value='編輯'/>
                        </div>
                    </div>
                </div>
                <div class="sub-comment__content card-body">
                    <p>
                        ${getContent}
                    </p>
                </div>
            </div>`;
        }
        
     }

        // 找到最上層的元素，在它之前新增一個 div 以及留言內容，然後把該元素移除
    function editDoneAndSubmit($eT, content, flag = true) {
        if(flag){
            $eT.parents('.editing') 
                .before(
                    $('<div>', { class: 'comment__content' })
                    .hide(350)
                    .append(content)
                    .show(350)
                ).hide(350, function() {
                    $(this).remove();
                }).show(350);
                            // console.log('主留言編輯完成')
                            // console.log(flag)
                            // console.log(typeof flag)
        } else {
            $eT.parents('.editing') 
                .before(
                    $('<div>', { class: 'sub-comment__content  card-body'})
                    .hide(350)
                    .append(content)
                    .show(350)
                ).hide(350, function() {
                    $(this).remove();
                }).show(350);

                        // console.log('子子子留言編輯完成')
                        // console.log(flag)
                        // console.log(typeof flag)
        }
        
    }

        // 建立主留言編輯留言的 textArea
    function editMainBox(elem, content, editId) {
        var $editForm = $('<form>', {
            class: 'editing',
            method: 'POST',
            action: './handle_edit_comment.php'
        }).hide(350)  // 350 毫秒是延續 remove 的時間
            .insertAfter(elem)
            .show(400)

        // 建立一個 div 容納 btn 並置入建立的表單內的第二個位置
        var $div1 = $('<div>', {
            class: 'form-row',
        }).prependTo($editForm)
        // 建立一個 div 容納 textarea 並置入建立的表單內的前面
        var $div2 = $('<div>', {
            class: 'form-row',
        }).prependTo($editForm)
        $('<textarea>', {
            class: 'content editDone',
            name: 'content',
            type: 'textarea',
            placeholder: '留言內容',
        }).html(content)  // 帶入原本的留言內容
        .appendTo($div2) // 附加到上面那個div

        // 建立一個 div 容納 btn 並置入建立的表單內的後面
        var $divBtn = $('<div>', {
            class: 'sub__btn edit__divBtn',
        }).appendTo($div1)
        
        $('<input>', {
            class: 'submit__btn edit__submit btn btn-success',
            type: 'submit',
            value: '更新留言'
        }).appendTo($divBtn)// 附加到上面那個div

        $('<input>', {
            class: 'hidden',
            type: 'hidden',
            name: 'parent_id',
            value: editId
        }).appendTo($divBtn)// 附加到上面那個div
    }

        // 建立子留言編輯留言的 textArea
    function editSubBox(elem, content, editId) {
        // 建立一個 form appendTo 目標元素
        var $editForm = $('<form>', {
            class: 'editing',
            method: 'POST',
            action: './handle_edit_comment.php'
        }).hide(350)
            .appendTo(elem)
            .show(400)

        // 建立一個 div 容納 btn 並置入建立的表單內的第二個位置
        var $div1 = $('<div>', {
            class: 'form-row',
        }).prependTo($editForm)
        // 建立一個 div 容納 textarea 並置入建立的表單內的前面
        var $div2 = $('<div>', {
            class: 'form-row',
        }).prependTo($editForm)
        $('<textarea>', {
            class: 'content editDone',
            name: 'content',
            type: 'textarea',
            placeholder: '留言內容',
        }).html(content)  // 帶入原本的留言內容
        .appendTo($div2) // 附加到上面那個div

        // 建立一個 div 容納 btn 並置入建立的表單內的後面
        var $divBtn = $('<div>', {
            class: 'sub__btn edit__divBtn',
        }).appendTo($div1)
        
        $('<input>', {
            class: 'submit__btn edit__submit  btn btn-success',
            type: 'submit',
            value: '更新留言'
        }).appendTo($divBtn)// 附加到上面那個div

        $('<input>', {
            class: 'hidden',
            type: 'hidden',
            name: 'parent_id',
            value: editId
        }).appendTo($divBtn)// 附加到上面那個div
    }

    function showTime() {
        const today = new Date();
        const y = today.getFullYear();
        let month = today.getMonth()+1;
        let day = today.getDate();
        let h = today.getHours();
        let m = today.getMinutes();
        let s = today.getSeconds();
        if(month < 10) {
            month = '0'+month
        }
        if(day < 10) {
            day = '0'+day
        }
        if(h < 10) {
            h = '0'+h
        }
        if(m < 10) {
            m = '0'+m
        }
        if(s < 10) {
            s = '0'+s;
        }
        return `${y}-${month}-${day} ${h}:${m}:${s}`;
    }

});
