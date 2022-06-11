import { Component, OnInit } from '@angular/core';
import { ApiService } from './../../../services/api.service'
import { AuthService } from './../../../services/auth.service'
import {ActivatedRoute } from '@angular/router'
import { Router } from '@angular/router';

@Component({
  selector: 'app-chat',
  templateUrl: './chat.component.html',
  styleUrls: ['./chat.component.css']
})
export class ChatComponent implements OnInit {
  isLogin: boolean = false
  userName: string|null = ''
  errorMessage: any;
  id:any = '';
  getFrom:any = '';
  getDatetime:any = '';
  getMessage:any = '';
  constructor(
    private Route: ActivatedRoute,
    private _api: ApiService, 
    private _auth: AuthService, 
    private _router:Router
    ) { }

  ngOnInit(): void {
    this.id = this.Route.snapshot.paramMap.get('id');
    this.isUserLogin(); 
    this.onLoad();
  }

  onLoad() {
    if (this.isLogin) {
      // console.log(this.userName);
      this._api.getTypeRequest('chats/chat/' + this.id).subscribe((res: any) => {
        if (res.status) { 
          // console.log(res)
          if (res.entry.length == 1) {
            if (this.userName == res.entry[0].username_to) {
              this.getFrom = res.entry[0].username_from;
              this.getDatetime = res.entry[0].datetime_created;
              this.getMessage = res.entry[0].message;
            } else {
              this.getMessage = 'Unauthorized to view this message!'
            }
            
          }

        } else { 
          console.log(res)
          // alert(res.msg)
        }
      });
    } else {
      this.getMessage = 'Unauthorized to view this message!'
    }
  }

  isUserLogin(){
    
    if(this._auth.getUserDetails() != null){
        this.isLogin = true;
        this.userName = this._auth.getUserDetails()!.substring(1, this._auth.getUserDetails()!.length-1)
    }
  }
}
