<template>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Root Category Table</h3>

                  <div class="card-tools">
                    <button class="btn btn-success" @click="newModal"> Add New Category <i class="fas fa-paperclip fa-fw"></i></button>
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
                    <tr v-for="cat in categories" :key="cat.id">
                      <td>{{ cat.id }}</td>
                      <td>{{ cat.name }}</td>
                      <td>{{ cat.description }}</td>
                      <td></td>
                      <td>
                          <a href="#" @click="editModal(cat)">
                            <i class="fas fa-edit"></i>
                          </a>
                          /
                          <a href="#" @click="deleteCategory(cat.id)">
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
                <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" v-show="!editmode" id="exampleModalLabel">New Category</h5>
                            <h5 class="modal-title" v-show="editmode" id="exampleModalLabel">Update Category</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <form @submit.prevent="editmode ? updateCategory() : createCategory()">
                            <div class="modal-body">
                                    <div class="form-group">
                                        <select class="form-control" v-model="form.root_category_id">
                                            <option>Select Root Category</option>
                                            <option v-for="root in rootcategories" :key="root.id" :value="root.id">{{ root.name }}</option>
                                        </select>
                                        <has-error :form="form" field="root_category_id"></has-error>
                                    </div>
                                    <div class="form-group">
                                            <input v-model="form.name" type="text" name="name" placeholder="Name Category"
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
                            <button type="submit" v-show="!editmode" class="btn btn-primary">Save</button>
                            <button type="submit" v-show="editmode" class="btn btn-primary">Update</button>
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
        data(){
            return{
                editmode : false,
                rootcategories : {},
                categories : {},
                form : new Form ({
                  id :'',
                  name :'',
                  description: '',
                  root_category_id: '',
                })
            }
        },

        methods: {
            newModal(){
                this.editmode = false
                this.form.reset(),
                $('#categoryModal').modal('show');
            },

            // method Post Category
            createCategory(){
                this.form.post('api/category')
                .then(()=>{
                        this.$Progress.start()
                        Fire.$emit('AfterAction');
                        $('#categoryModal').modal('hide');

                            Toast.fire({
                                type: 'success',
                                title: 'successfully Created'
                            })

                    this.$Progress.finish()
                    })
                    .catch(()=>{
                            Toast.fire({
                                type: 'error',
                                title: 'successfully Created'
                            })
                        this.$Progress.fail()
                    })
            },

            // method show data Category

            loadCategory(){
                axios.get("api/category").then(({data}) => (this.categories = data.data));
                axios.get("api/root-category").then(({ data }) => (this.rootcategories = data.data));
            },

            deleteCategory(id){
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
                      this.form.delete('api/category/'+id).then(()=>{
                        Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                        )
                        Fire.$emit('AfterAction');
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

            editModal(cat){
                this.editmode = true;
                this.form.reset(),
                $('#categoryModal').modal('show');
                this.form.fill(cat);
            },

            updateCategory(id){
                this.$Progress.start();
                this.form.put('api/category/'+this.form.id)
                .then(()=>{
                    this.$Progress.finish()
                    Fire.$emit('AfterAction');
                    $('#categoryModal').modal('hide');
                        Toast.fire({
                            type: 'success',
                            title: 'successfully updated'
                        })
                }).catch(()=>{
                    this.$Progress.fail()
                    Toast.fire({
                            type: 'success',
                            title: 'successfully updated'
                        })
                })
            },

        },

        created() {
            this.loadCategory();
            Fire.$on('AfterAction', ()=> {
                this.loadCategory();
            })
        }
    }
</script>
