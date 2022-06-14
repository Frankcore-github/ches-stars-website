export default class Gate{

    constructor(user){
        this.user = user
    }

    isAdmin(){
        return this.user.usertype === 'admin'
    }

    isStaff(){
        return this.user.usertype === 'staff'
    }

    isStudent(){
        return this.user.usertype === 'student'
    }

    allowedExam(){
        return this.user.student.examstatus === 'allowed'
    }
}