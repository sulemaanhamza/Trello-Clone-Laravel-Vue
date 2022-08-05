<template>
    <div>
        <div class='layout'>

            <div class='layout__list' v-for="category in categoriesWithCards" v-bind:key="category.id">
                <div class="list__header">
                    <p><strong>{{ category.title || '' }}</strong></p>
                    <button class="list__header--delete" @click="deleteCategory(category.id)">Delete</button>
                </div>
                <draggable ghost-class="ghost" v-model="category.cards" group="cards" @start="drag=true" @end="updateCards(category.id,$event)" :data-category="category.id">
                    <div class='list__card' v-for="card in category.cards" v-bind:key="card.name" :id="card.id">
                        <label class='card__label'>
                            <a href="javascript:;" @click="showSingleCardModal(category.id,card.id)">{{ card.title || '' }}</a>
                        </label>
                        <a href="">x</a>
                    </div>
                    <div  slot="header" class="card__add">
                        <button class="card__add--btn" @click="addNewCard(category.id, $event)">+ Add New Card</button>
                    </div>
                </draggable>
            </div>

            <div class='layout__list'>
                <div class="list__header">
                    <p><strong>New Category</strong></p>
                </div>
                <div class='list__card'>
                    <button class="card__add--btn" @click="$modal.show('add-new-category')">+ Add New Category</button>
                 </div>
            </div>

        </div>

        <modal name="update-existing-card" transition="pop-out" :width="modalWidth" :focus-trap="true" :height="500" :clickToClose="false">
            <div class="AddCard__modal">
                <fieldset>
                    <legend>Update Card:</legend>
                    <div>
                        <label for="">Title *</label>
                        <input type="text" v-model="updateCardPayload.title" placeholder="e.g. lorem ipsum" required>
                    </div>
                    <div>
                        <label for="">Description</label>
                        <textarea v-model="updateCardPayload.description" cols="30" rows="10" placeholder="e.g. lorem ipsum"></textarea>
                    </div>
                    <div>
                        <button @click="updateCard(updateCardPayload.id)"> Save </button>
                        <button @click="closeUpdateCardModal"> Cancel</button>
                    </div>
                    <div class="AddCard__modal--errorTxt" v-text="updateCardPayload.error"></div>
                </fieldset>
            </div>
        </modal>

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

        <modal name="add-new-category" transition="pop-out" :width="modalWidth" :focus-trap="true" :height="300" :clickToClose="false">
            <div class="AddCard__modal">
                <fieldset>
                    <legend>Add New Category:</legend>
                    <div>
                        <label for="">Title *</label>
                        <input type="text" v-model="addNewCategoryPayload.title" placeholder="e.g. lorem ipsum" required>
                    </div>
                    <div>
                        <button @click="saveNewCategory"> + Create </button>
                        <button @click="closeAddNewCategoryModal"> Cancel</button>
                    </div>
                    <div class="AddCard__modal--errorTxt" v-text="addNewCategoryPayload.error"></div>
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
            },
            updateCardPayload: {
                id: null,
                title: null,
                description: null,
                error: null
            },
            addNewCategoryPayload: {
                title: null,
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
                axios.put(`/api/cards/${ItemId}/update-category?access_token=3|Etv3Hplu9TikAnUc31gAtvMmZyYvqrk0BicUSZdo&category=${targetCategory}`)
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
        },


        saveNewCategory() {

            if(!this.addNewCategoryPayload.title || this.addNewCategoryPayload.title == '')
            {
                this.addNewCategoryPayload.error = 'Category title is required';
                return false;
            }
            else
            {
                 this.addNewCategoryPayload.error = null;


                axios.post(`/api/categories?access_token=3|Etv3Hplu9TikAnUc31gAtvMmZyYvqrk0BicUSZdo`, {
                    title: this.addNewCategoryPayload.title,
                })
                    .then(resp => {
                        this.closeAddNewCategoryModal();
                        this.fetchBoardData();
                    })
                    .catch(err => {
                        this.closeAddNewCategoryModal();
                        console.log(err);
                    })
            }
        },
        closeAddNewCategoryModal() {
            this.addNewCategoryPayload = {
                title: null,
                error: null
            }

            this.$modal.hide('add-new-category')
        },
        deleteCategory(category) {

            if(confirm('Are you sure? This will delete category and all linked cards.'))
            {
                axios.delete(`/api/categories/${category}?access_token=3|Etv3Hplu9TikAnUc31gAtvMmZyYvqrk0BicUSZdo`)
                    .then(resp => {
                        this.fetchBoardData();
                    })
                    .catch(err => {
                        this.closeAddNewCardModal();
                        console.log(err);
                    })
            }
        },
        showSingleCardModal(category,card) {

            let cat = this.categoriesWithCards.find(ct => ct.id === category);

            if(cat && cat.cards)
            {
                let cardObject = cat.cards.find(crd => crd.id === card);

                if(cardObject)
                {
                    this.updateCardPayload.id = cardObject.id;
                    this.updateCardPayload.title = cardObject.title;
                    this.updateCardPayload.description = cardObject.description;

                    this.$modal.show('update-existing-card');
                }
            }

        },

        closeUpdateCardModal() {
            this.updateCardPayload = {
                title: null,
                description: null,
                error: null
            }

            this.$modal.hide('update-existing-card')
        },

        updateCard(card) {

            if(!this.updateCardPayload.title || this.updateCardPayload.title == '')
            {
                this.updateCardPayload.error = 'Card title is required';
                return false;
            }
            else if(!card || card == '')
            {
                this.updateCardPayload.error = 'Something went wrong';
                return false;
            }
            else
            {
                this.updateCardPayload.error = null;


                axios.put(`/api/cards/${card}?access_token=3|Etv3Hplu9TikAnUc31gAtvMmZyYvqrk0BicUSZdo`, {
                    title: this.updateCardPayload.title,
                    description: this.updateCardPayload.description
                })
                    .then(resp => {
                        this.closeUpdateCardModal();
                        this.fetchBoardData();
                    })
                    .catch(err => {
                        this.closeUpdateCardModal();
                        console.log(err);
                    })
            }
        }
    }
}
</script>

<style>

</style>
