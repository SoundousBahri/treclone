<template>
  <div class="container">
    <div class="row justify-content-center">
      <draggable element="div" class="col-md-12" v-model="categories" :options="dragOptions2">
        <transition-group class="row">

          <div class="col-md-4" v-for="(element,i) in categories" :key="element.id">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">{{element.name}}</h4>
              </div>
              <div class="card-body card-body-dark">
                <draggable :options="dragOptions" element="div" @end="changeOrder" v-model="element.tasks">
                  <transition-group :data-id="element.id">
                    <div v-for="(task,index) in element.tasks" :key="i+','+index" class="transit-1"
                         :data-id="task.id">
                      <div class="small-card" v-if="task.name.trim() || task === editingTask">
                        <textarea ref="textarea" v-if="task === editingTask" class="text-input"
                                  @keyup.enter="endEditing(task,i,index)"
                                  @blur="endEditing(task,i,index)" v-model="task.name" title="task Name"></textarea>
                        <label v-if="task !== editingTask" @dblclick="editTask(task,i)">{{ task.name
                          }}</label>
                      </div>
                    </div>
                  </transition-group>
                </draggable>
                <div class="small-card btn w-100">
                  <h5 class="text-center" @click="addTask(element)">Add new card</h5>
                </div>
              </div>
            </div>
          </div>
        </transition-group>
      </draggable>
    </div>
  </div>
</template>

<style scoped>
  .card {
    border        : 0;
    border-radius : 0.5rem;
  }

  .transit-1 {
    transition : all 1s;
  }

  .small-card {
    padding       : 1rem;
    background    : #F5F8FA;
    margin-bottom : 5px;
    border-radius : .25rem;
  }

  .card-body-dark {
    background-color : #CCCCCC;
  }

  textarea {
    overflow : visible;
    outline  : 1px dashed black;
    border   : 0;
    padding  : 6px 0 2px 8px;
    width    : 100%;
    height   : 100%;
    resize   : none;
  }
</style>

<script>
    import draggable from 'vuedraggable'

    export default {
        components: {
            draggable
        },
        data() {
            return {
                categories: [],
                editingTask: null
            }
        },
        methods: {
            addTask(category) {
                if (!this.editingTask || this.editingTask.category_id !== category.id) {
                    this.editingTask = {name: '', category_id: category.id, order: category.tasks.length};
                    if (category.tasks[category.tasks.length-1].id) {
                        category.tasks.push(this.editingTask);
                    } else {
                        category.tasks[category.tasks.length-1] = this.editingTask;
                    }
                }
                this.$nextTick(() => {
                    console.log(this.$refs.textarea);
                    this.$refs.textarea[0].focus();
                });

            },
            loadTasks() {
                this.categories.map(category => {
                    axios.get(`api/category/${category.id}/tasks`).then(response => {
                        category.tasks = response.data
                    })
                })
            },
            changeOrder(data) {
                if (data.newIndex !== data.oldIndex || data.to.dataset.id !== data.from.dataset.id) {
                    axios.patch(`api/task/${data.item.dataset.id}`, {
                        order: data.newIndex,
                        category_id: data.to.dataset.id
                    }).then(response => {

                    });
                }
            },
            endEditing(task, categoryIndex, taskIndex) {
                if (!this.editingTask) return;
                this.editingTask = null;


                if (task.name.trim()) {
                    if (task.id !== undefined) {
                        axios.patch(`api/task/${task.id}`, {name: task.name.trim()}).then(response => {

                        })
                    } else {
                        axios.post('api/task', task).then(response => {
                            this.categories[categoryIndex].tasks[taskIndex] = response.data.data
                        })
                    }
                } else {
                    //this.categories[categoryIndex].tasks[taskIndex]=null;
                }
            },
            editTask(task) {

                this.editingTask = task;

            }
        },
        mounted() {
            let token = localStorage.getItem('jwt');
            axios.defaults.headers.common['Content-Type'] = 'application/json';
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
            axios.get('api/category').then(response => {
                response.data.forEach((data) => {
                    this.categories.push({
                        id: data.id,
                        name: data.name,
                        tasks: []
                    })
                })
                this.loadTasks()
            })
        },
        computed: {
            dragOptions() {
                return {
                    animation: 1,
                    group: 'description',
                    ghostClass: 'ghost'
                };
            },
            dragOptions2() {
                return {
                    group: 'cards',
                    handle: '.card-header',

                };
            }
        },
        beforeRouteEnter(to, from, next) {
            if (!localStorage.getItem('jwt')) {
                return next('login')
            }
            next()
        }
    }
</script>