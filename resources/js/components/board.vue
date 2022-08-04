<template>
    <div>
        <div class='layout'>

            <div class='layout__list'>
                <div class="list__header">
                    <p><strong>Column 1</strong></p>
                    <a href="javascript:0">Delete</a>
                </div>
                <draggable v-model="myArray" group="people" @start="drag=true" @end="drag=false">
                    <div class='list__card'>
                        <label class='card__label'>Trello board mockup</label>
                        <!-- <div class='card__date'>August 4th</div> -->
                    </div>
                    <div class='list__card'>
                        <label>Test card by Suleman</label>
                    </div>
                </draggable>
            </div>

            <div class='layout__list'>
                <div class="list__header">
                    <p><strong>Column 2</strong></p>
                    <a href="javascript:0">Delete</a>
                </div>
                <draggable v-model="myArray" group="people" @start="drag=true" @end="drag=false">
                    <div class='list__card'>
                        <label>Lorem ipsum dolor sit amet..</label>
                    </div>
                </draggable>
            </div>

            <div class='layout__list'>
                <div class="list__header">
                    <p><strong>Column 3</strong></p>
                    <a href="javascript:0">Delete</a>
                </div>
                <draggable v-model="myArray" group="people" @start="drag=true" @end="drag=false">
                    <div class='list__card'>
                        <label>Lorem ipsum dolor sit amet consectetur.</label>
                    </div>
                    <div class='list__card'>
                        <label>Lorem ipsum dolor sit amet consectetur.</label>
                    </div>
                </draggable>
            </div>

        </div>
    </div>
</template>

<script>
import draggable from 'vuedraggable';

export default {

    components: {
            draggable,
    },
}
</script>

<style>

</style>
