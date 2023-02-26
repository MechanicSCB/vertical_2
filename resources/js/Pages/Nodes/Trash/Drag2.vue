<script setup>
let myArray = [
    {
        'id': 1,
        'name': 'cat1',
        'nodes': [
            {'id': 2, 'name': 'cat2'},
            {'id': 3, 'name': 'cat3'},
        ],
    },
    {'id':2, 'name':'cat2'},
    {'id':3, 'name':'cat3'},
];

function dragStart(ev) {
    ev.dataTransfer.effectAllowed='move';
    ev.dataTransfer.setData("Text", ev.target.getAttribute('id'));
    ev.dataTransfer.setDragImage(ev.target,100,100);
    return true;
}

function dragEnter(ev) {
    ev.preventDefault();
    return true;
}

function dragOver(ev) {
    ev.preventDefault();
}

function dragDrop(ev) {
    var data = ev.dataTransfer.getData("Text");
    ev.target.appendChild(document.getElementById(data));
    ev.stopPropagation();
    return false;
}
</script>
<template>
    <div class="px-12">
        <h1>Drag the blue boxes into the red box and back</h1>
        <div id="big" @dragenter="dragEnter($event)"
             @drop="dragDrop($event)"
             @dragover="dragOver($event)"></div>

        <section id="section"  @dragenter="dragEnter($event)"
                 @drop="dragDrop($event)"
                 @dragover="dragOver($event)">

            <div class="drag" id="boxA" draggable="true" @dragstart="dragStart($event)"></div>
            <div class="drag" id="boxB" draggable="true" @dragstart="dragStart($event)"></div>
        </section>

    </div>
</template>
<style>
.drag {
    width: 200px;
    height: 200px;
    background: blue;
    display: inline-block;
    margin-right: 10px;
}
#boxB{
    background-color: yellow;
    margin-right: 0px;
}
#big {
    width: 415px;
    height: 400px;
    background: red;
    margin: 20px auto;
}
section {
    width: 415px;
    height: 200px;
    background:gray;
    margin: 20px auto;
}
</style>
