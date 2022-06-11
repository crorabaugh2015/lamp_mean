import { Component, OnInit } from '@angular/core';
import { ApiService } from './../../../services/api.service'
import { AuthService } from './../../../services/auth.service'
import {NgForm} from '@angular/forms';
// import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
@Component({
  selector: 'app-create',
  templateUrl: './create.component.html',
  styleUrls: ['./create.component.css']
})
export class CreateComponent implements OnInit {
  isLogin: boolean = false
  userName: string|null = ''
  errorMessage: any
  constructor(
    private _api: ApiService, 
    private _auth: AuthService, 
    private _router:Router
  ) { }

  ngOnInit() {
    this.isUserLogin(); 
  }

  onSubmit(form: NgForm) {
    if (this.isLogin) {
      console.log(form.value);
      form.value.username_from=this.userName;
      console.log(form.value);
      this._api.postTypeRequest('chats/create', form.value).subscribe((res: any) => {
        if (res.status) { 
          console.log(res)
          // this._auth.setDataInLocalStorage('userData', JSON.stringify(res.data));  
          // this._auth.setDataInLocalStorage('token', res.token);  
          this._router.navigate(['/']);
        } else { 
          console.log(res)
          // alert(res.msg)
        }
      });
    }
  }

  isUserLogin(){
    
    if(this._auth.getUserDetails() != null){
        this.isLogin = true;
        this.userName = this._auth.getUserDetails()!.substring(1, this._auth.getUserDetails()!.length-1)
    }
  }

}
