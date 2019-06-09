<template>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Root Category Table</h3>

                  <div class="card-tools">
                    <button class="btn btn-success" @click="newModal"> Add New Root Category <i class="fab fa-ethereum fa-fw"></i></button>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover">
                    <tbody><tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Description</th>
                      <th>Registered At</th>
                      <th>Actions</th>
                    </tr>
                    <tr v-for="category in rootcategories" :key="category.id">
                      <td>{{ category.id }}</td>
                      <td>{{ category.name }}</td>
                      <td>{{ category.description }}</td>
                      <td>{{ category.created_at | myDate }}</td>
                      <td>
                          <a href="#" @click="editModal(category)">
                            <i class="fas fa-edit"></i>
                          </a>
                          /
                          <a href="#" @click="deleteRootCategory(category.id)">
                              <i class="fas fa-trash"></i>
                          </a>
                      </td>
                    </tr>
                  </tbody></table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" v-show="editmode" id="exampleModalLabel">Update Root Category</h5>
                            <h5 class="modal-title" v-show="!editmode" id="exampleModalLabel">New Root Category</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <form @submit.prevent="editmode ? updateRootCategory() : createRootCategory()">
                            <div class="modal-body">
                                    <div class="form-group">
                                            <input v-model="form.name" type="text" name="name" placeholder="Name Root Category"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                            <has-error :form="form" field="name"></has-error>
                                    </div>
                                    <div class="form-group">
                                            <textarea v-model="form.description" name="description"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('description') }"> description (optional) </textarea>
                                             <has-error :form="form" field="description"></has-error>
                                    </div>

                            </div>

                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                            <button v-show="!editmode" type="submit" class="btn btn-primary">Save</button>
                            </div>
                            </form>
                        </div>
                        </div>
                    </div>

    </div><!-- /.container-fluid -->
</div>
</template>

<script>
        export default {
            data () {
                return {
                editmode: false,
                rootcategories : {},

                form: new Form({
                    id: '',
                    name: '',
                    description: '',
                })
                }
            },

            methods: {
                updateRootCategory(id){
                    // console.log('aaa')
                    this.$Progress.start();
                    this.form.put('api/root-category/'+this.form.id)
                    .then(()=>{
                        this.$Progress.finish()
                        Fire.$emit('AfterCreated');
                        $('#exampleModal').modal('hide');
                            Toast.fire({
                                type: 'success',
                                title: 'successfully Created'
                            })
                    }).catch(()=>{
                            this.$Progress.fail()
                            Toast.fire({
                                type: 'error',
                                title: 'Failed Update'
                            })
                    });
                },
                editModal(category){
                    this.editmode = true;
                    this.form.reset(),
                    $('#exampleModal').modal('show');
                    this.form.fill(category);
                },
                newModal(){
                    this.editmode = false;
                    this.form.reset(),
                    $('#exampleModal').modal('show');
                },
                deleteRootCategory(id){
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                            }).then((result) => {
                               if (result.value) {
                                this.form.delete('api/root-category/'+id).then(()=>{
                                        Swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                        )
                                        Fire.$emit('AfterCreated');
                                }).catch(()=>{
                                        Swal.fire(
                                        'Deleted!',
                                        'gagal dihapus.',
                                        'success'
                                        )
                                });
                            }
                        })
                },

                loadRootCategory(){
                    axios.get("api/root-category").then(({ data }) => (this.rootcategories = data.data));
                },

                createRootCategory(){

                    this.form.post('/api/root-category')
                    .then(()=>{
                        this.$Progress.start()
                        Fire.$emit('AfterCreated');
                        $('#exampleModal').modal('hide');

                            Toast.fire({
                                type: 'success',
                                title: 'successfully Created'
                            })

                    this.$Progress.finish()
                    })
                    .catch(()=>{
                        this.$Progress.fail()
                    })

                }
            },

            created() {
                this.loadRootCategory();
                Fire.$on('AfterCreated',() => {
                    this.loadRootCategory();
                })
            }
        }
</script>
