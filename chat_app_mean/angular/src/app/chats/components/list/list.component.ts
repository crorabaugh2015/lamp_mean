import { Component, OnInit } from '@angular/core';
import { ApiService } from './../../../services/api.service'
import { AuthService } from './../../../services/auth.service'
import {NgForm} from '@angular/forms';
// import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';

@Component({
  selector: 'app-list',
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.css']
})
export class ListComponent implements OnInit {
  isLogin: boolean = false
  userName: string|null = ''
  errorMessage: any;
  unseen_array: Array<any> = []
  seen_array: Array<any> = []
  constructor(
    private _api: ApiService, 
    private _auth: AuthService, 
    private _router:Router
  ) { }

  ngOnInit() {
    this.isUserLogin(); 
    this.onLoad();
  }
  onLoad() {
    if (this.isLogin) {
      // console.log(this.userName);
      this._api.getTypeRequest('chats/list/' + this.userName).subscribe((res: any) => {
        if (res.status) { 
          this.unseen_array = res.unseen;
          
          this.seen_array = res.seen;

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
