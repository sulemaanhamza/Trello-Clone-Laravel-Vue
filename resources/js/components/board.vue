<template>
    <div>
        <div class='layout'>

            <div class='layout__list' v-for="category in categoriesWithCards" v-bind:key="category.id">
                <div class="list__header">
                    <p><strong>{{ category.title || '' }}</strong></p>
                    <button class="list__header--delete">Delete</button>
                </div>
                <draggable ghost-class="ghost" v-model="category.cards" group="cards" @start="drag=true" @end="updateCards(category.id,$event)" :data-category="category.id">
                    <div class='list__card' v-for="card in category.cards" v-bind:key="card.name" :id="card.id">
                        <label class='card__label'>{{ card.title || '' }}</label>
                        <!-- <div class='card__date'>August 4th</div> -->
                    </div>
                    <div  slot="header" class="card__add">
                        <button class="card__add--btn" @click="addNewCard(category.id, $event)">+ Add New Card</button>
                    </div>
                </draggable>
            </div>

        </div>

        <modal name="add-new-card" transition="pop-out" :width="modalWidth" :focus-trap="true" :height="500" :clickToClose="false">
            <div class="AddCard__modal">
                <fieldset>
                    <legend>Add New Card:</legend>
                    <div>
                        <label for="">Title *</label>
                        <input type="text" v-model="addNewCardPayload.title" placeholder="e.g. lorem ipsum" required>
                    </div>
                    <div>
                        <label for="">Description</label>
                        <textarea v-model="addNewCardPayload.description" cols="30" rows="10" placeholder="e.g. lorem ipsum"></textarea>
                    </div>
                    <div>
                        <button @click="saveNewcard"> + Create </button>
                        <button @click="closeAddNewCardModal"> Cancel</button>
                    </div>
                    <div class="AddCard__modal--errorTxt" v-text="addNewCardPayload.error"></div>
                </fieldset>
            </div>
        </modal>
    </div>
</template>

<script>
import draggable from 'vuedraggable';
import axios from 'axios';
const MODAL_WIDTH = 656
export default {

    components: {
            draggable,
    },

    data() {
        return {
            categoriesWithCards: [],
            modalWidth: MODAL_WIDTH,
            addNewCardPayload: {
                category_id: null,
                title: null,
                description: null,
                error: null
            }

        }
    },

    created() {

        this.fetchBoardData();

        this.modalWidth = window.innerWidth < MODAL_WIDTH ? MODAL_WIDTH / 2 : MODAL_WIDTH
    },

    methods: {

        fetchBoardData() {

            axios.get('/api/categories?access_token=3|Etv3Hplu9TikAnUc31gAtvMmZyYvqrk0BicUSZdo')
                .then(resp => {
                    const data = resp.data;

                    if(data.categories)
                    {
                        this.categoriesWithCards = data.categories;
                    }

                })
                .catch(err => {
                    console.log(err);
                })
        },


        updateCards(category,data) {

            const sourceCategory = category;
            const targetCategory = data.to.getAttribute('data-category');
            const ItemId = data.clone.getAttribute('id');

            if(targetCategory !== undefined && ItemId !== undefined)
            {
                axios.put(`/api/cards/${ItemId}?access_token=3|Etv3Hplu9TikAnUc31gAtvMmZyYvqrk0BicUSZdo&category=${targetCategory}`)
                    .then(resp => {
                        this.fetchBoardData();
                    })
                    .catch(err => {
                        console.log(err);
                    })
            }

        },


        addNewCard(category, data) {
            this.addNewCardPayload.category_id = category;
            this.$modal.show('add-new-card')
        },
        saveNewcard() {

            if(!this.addNewCardPayload.title || this.addNewCardPayload.title == '')
            {
                this.addNewCardPayload.error = 'Card title is required';
                return false;
            }
            else if(!this.addNewCardPayload.category_id)
            {
                this.addNewCardPayload.error = 'Something went wrong';
                return false;
            }
            else
            {
                this.addNewCardPayload.error = null;


                axios.post(`/api/cards?access_token=3|Etv3Hplu9TikAnUc31gAtvMmZyYvqrk0BicUSZdo`, {
                    category_id: this.addNewCardPayload.category_id,
                    title: this.addNewCardPayload.title,
                    description: this.addNewCardPayload.description
                })
                    .then(resp => {
                        this.closeAddNewCardModal();
                        this.fetchBoardData();
                    })
                    .catch(err => {
                        this.closeAddNewCardModal();
                        console.log(err);
                    })
            }
        },
        closeAddNewCardModal() {
            this.addNewCardPayload = {
                category_id: null,
                title: null,
                description: null
            }
            this.$modal.hide('add-new-card');
        }
    }
}
</script>

<style>

</style>
