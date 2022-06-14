<template>
    <div>
      <div class="card-footer">
      <form @submit.prevent="editMode ? updateComment() : addComment()" @keydown="form.onKeydown($event)" autocomplete="off">
        <!-- .img-push is used to add margin to elements next to floating images -->
        <div class="input-group">
          <input v-model="form.body" type="text" name="body" class="form-control form-control-sm" 
          :class="{ 'is-invalid': form.errors.has('body') }" placeholder="Press enter to post comment">
          <span class="input-group-append">
            <button :disabled="form.busy" v-show="!editMode" type="submit" class="btn btn-outline-primary btn-sm ml-1 mb-1">Send</button>
            <button :disabled="form.busy" v-show="editMode" type="submit" class="btn btn-outline-success btn-sm ml-1 mb-1">Update</button>
          </span>
          <has-error :form="form" field="body"></has-error>
        </div>
      </form>
    </div>
    <!-- /.attachment-block -->
    <div class="comment-count">
    <span class="p-4">{{comment_count}} comments <i class="fa fa-comments" aria-hidden="true"></i></span>
    </div>
    
    <!-- /.card-footer -->
      <div class="card-footer card-comments" v-for="comment in this.comment" :key="comment.id">
        <!-- /.card-comment -->
        <div class="card-comment"> <!-- Loop here -->
        <img v-if="comment.user.staff" class="img-circle img-sm" :src="profile(comment.user.staff.photo)" alt="User Image">
        <img v-else class="img-circle img-sm" :src="profile(comment.user.student.photo)" alt="User Image">
          <div class="comment-text">
            <span class="username">
              <template v-if="comment.user.staff">
              {{ comment.user.staff.first_name }} {{comment.user.staff.last_name}}
              </template>
              <template v-else>
              {{ comment.user.student.first_name }} {{comment.user.student.last_name}}
              </template>
              <span class="text-muted float-right"><i v-if="comment.user.id == user" @click="edit(comment, comment.id)" class="fa fa-edit pr-2">edit</i>{{comment.updated_at | classDate}}</span>
            </span><!-- /.username -->  
            {{comment.body}}
          </div>
          <!-- /.comment-text -->
        </div>
        <!-- /.card-comment -->
      </div>
    </div>
</template>

<script>
    export default {
      props: ['id'],
      data(){
        return{
          comment_id:0,
          comment_count: 0,
          comment:{},
          user: 0,
          editMode: false,
          form: new Form({
            note_id: this.id,
            body: ''
          })
        }
      },
      methods: {
        profile(image){
          if(image === null)
            return "/storage/img/profile/default.png"
          else
            return "/storage/img/profile/"+ image
        },
        addComment(){
          this.editMode = false;
          this.$Progress.start()
          this.form.post('/api/comment')
          .then(() => {
              this.$Progress.finish()
          })
          .catch(() => {
              this.$Progress.fail()
          })
          .finally(() => {
              this.form.body = ''
          });
        },
        getComments(){
            axios.get('/api/commentnote/'+this.id).then(({data}) => (this.comment = data.object1, this.comment_count = data.object2, this.user = data.user))
        },
        edit(comment,id){
          this.comment_id= id;
          this.form.clear();
          this.editMode = true;
          this.form.reset();
          this.form.fill(comment)
        },
        updateComment(){
          this.$Progress.start()
          this.form.patch('/api/comment/'+this.comment_id)
          .then(() => {
              this.$Progress.finish()
          })
          .catch(() => {
              this.$Progress.fail()
          })
          .finally(() => {
              this.editMode = false;
              this.form.body = ''
          });
        }
      },
      mounted() {
        this.getComments()
        Echo.channel('Comment.'+this.form.note_id)
            .listen('CommentEvent', (event) =>{
              this.comment = (event.comment),
              this.comment_count = (event.comment.length)
            })
      }
    }
</script>
