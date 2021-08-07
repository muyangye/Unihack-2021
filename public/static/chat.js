// initialize page
const user = document.querySelector('#username').textContent;
const who = document.querySelector('#who').textContent;
const avatar = document.querySelector('#avatar').textContent;
var messages = JSON.parse(document.querySelector('#messages').textContent);
// console.log(user);
console.log(messages);

const getAllMessage = (nameToChat, image) => {
    // 请求nameToChat 和 user 的来聊天记录
    // messages: dict array
    // messages = [
    //     {
    //         'send': true,
    //         'time': '2021-03-23 19:34:2',
    //         'text': '1'
    //     },
    //     {
    //         'send': false,
    //         'time': '2021-03-23 19:34:2',
    //         'text': '2'
    //     },
    //     {
    //         'send': true,
    //         'time': '2021-03-23 19:34:2',
    //         'text': '1'
    //     },
    //     {
    //         'send': false,
    //         'time': '2021-03-23 19:34:2',
    //         'text': '2'
    //     },
    //     {
    //         'send': true,
    //         'time': '2021-03-23 19:34:2',
    //         'text': '1'
    //     },
    //     {
    //         'send': false,
    //         'time': '2021-03-23 19:34:2',
    //         'text': '2'
    //     }
    // ]
    // messages = {$messages};

    const friendImage = document.querySelector('#friend-image');
    friendImage.src = image;

    const friendName = document.querySelector('#friend-name');
    friendName.textContent = nameToChat;

    for (message of messages) {
        var timeSplit = message['time'].split(' ')[1].split(':');

        renderMessage(message['send'] ? user : nameToChat,
            timeSplit[0] + ':' + timeSplit[1], message['text']);
    }
}

const getPeopleList = () => {
    // 请求与user 最匹配的五个人
    avator = [
        {
            'myAvator': '1022709.jpeg',
            'whoAvator': "Yutong",
            'age': 12
        },
        {
            'myAvator': '1022709.jpeg',
            'whoAvator': "Muyang",
            'age': 12
        },
        {
            'myAvator': '1022709.jpeg',
            'whoAvator': "Yilin",
            'age': 12
        },
        {
            'myAvator': '1022709.jpeg',
            'whoAvator': "Peter",
            'age': 12
        },
    ]
    for (people of avator) {
        renderPeople(people['myAvator'], people['whoAvator'], people['age']);
    }
}


const btn = document.querySelector('#but');

btn.onclick = () => {
    // add to chatbox

    const msg = document.querySelector('#msg');
    var var_text = msg.value;
    if (var_text === '') {
        return;
    }
    var current_time = new Date();
    var hour = current_time.getHours();
    var minute = current_time.getMinutes();
    var var_time = hour + ':' + minute;
    var var_user = user;

    renderMessage(var_user, var_time, var_text);
    msg.value = '';

    var current_time = new Date();
    var y = current_time.getFullYear();
    var mo = current_time.getMonth();
    var d = current_time.getDate();
    var h = current_time.getHours();
    var m = current_time.getMinutes();
    var s = current_time.getSeconds();
    var backendTime = y + '-' + mo + '-' + d + ' ' + h + ':' + m + ':' + s;
    // TODO: send to backend
    const to_backend = {
        'user': var_user,
        'text': var_text,
        'time': backendTime,
        'who': who
    };
    // console.log()
    $.ajax({
        url: "{:url('App/newMessage')}",
        type: 'post',
        dataType: 'json',
        data : to_backend,
    })
    .done(function(e)
    {
        if (e.code == 1) {
            toastr['success'](e.message, '已发送');
        } else {
            toastr['warning'](e.message, '发送失败');
        }
    })
    .fail(function()
    {
        toastr['error']("通讯出错", '发送失败');
    });

}

// btn.click(function(){
//     // add to chatbox

//     const msg = document.querySelector('#msg');
//     var var_text = msg.value;
//     if (var_text === '') {
//         return;
//     }
//     var current_time = new Date();
//     var hour = current_time.getHours();
//     var minute = current_time.getMinutes();
//     var var_time = hour + ':' + minute;
//     var var_user = user;

//     renderMessage(var_user, var_time, var_text);
//     msg.value = '';

//     var current_time = new Date();
//     var y = current_time.getFullYear();
//     var mo = current_time.getMonth();
//     var d = current_time.getDate();
//     var h = current_time.getHours();
//     var m = current_time.getMinutes();
//     var s = current_time.getSeconds();
//     var backendTime = y + '-' + mo + '-' + d + ' ' + h + ':' + m + ':' + s;
//     // TODO: send to backend
//     const to_backend = {
//         'user': var_user,
//         'text': var_text,
//         'time': backendTime,
//         'who': who
//     };
//     // console.log()
//     $.ajax({
//         url: "{:url('App/newMessage')}",
//         type: 'post',
//         dataType: 'json',
//         data : to_backend,
//     })
//     .done(function(e)
//     {
//         if (e.code == 1) {
//             toastr['success'](e.message, '已发送');
//         } else {
//             toastr['warning'](e.message, '发送失败');
//         }
//     })
//     .fail(function()
//     {
//         toastr['error']("通讯出错", '发送失败');
//     });
// })


const renderPeople = (var_image, var_username, var_age) => {
    const peopleList = document.querySelector('.people-list ul');

    const people = document.createElement('li');
    people.classList.add('clearfix');

    const image = document.createElement('img');
    image.src = var_image;
    image.alt = '';
    people.appendChild(image);

    const info = document.createElement('div');
    info.classList.add('about');
    const name = document.createElement('div');
    name.classList.add('name');
    name.textContent = var_username;
    info.appendChild(name);
    const age = document.createElement('div');
    age.textContent = var_age + ' Years Old';
    age.classList.add('status');
    info.appendChild(age);
    people.appendChild(info);

    peopleList.appendChild(people);
    // onclick
    people.onclick = () => {
        getAllMessage(var_username, var_image);
    }

}
const renderMessage = (var_username, var_time, var_text) => {
    const chatMessages = document.querySelector('.chat-history ul');

    const message = document.createElement('li');
    message.classList.add('clearfix');

    const time = document.createElement('span');
    time.classList.add('message-data-time');
    time.textContent = var_time;

    const timeContainer = document.createElement('div');
    timeContainer.appendChild(time);
    message.appendChild(timeContainer);
    if (var_username === user) {
        timeContainer.classList.add('message-data', 'text-right');
    } else {
        timeContainer.classList.add('message-data');
    }

    const text = document.createElement('div');
    text.textContent = var_text;
    if (var_username === user) {
        text.classList.add('message', 'other-message', 'float-right');

    } else {
        text.classList.add('message', 'my-message');
    }

    message.appendChild(text);
    chatMessages.appendChild(message);
    // 
    // setTimeout(function () {
    //     document.querySelector('.chat-history').scrollTop(80);
    // }.bind(this), 1000);
    var chat_history = document.querySelector(".chat-history");
    chat_history.scrollTo(0, chat_history.scrollHeight);

}
getPeopleList();
getAllMessage(who, avatar);
