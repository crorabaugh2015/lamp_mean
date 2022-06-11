import { Component, OnInit } from '@angular/core';
import { ApiService } from './../../../services/api.service'
import {NgForm} from '@angular/forms';
import { Router } from '@angular/router';
@Component({
  selector: 'app-daterange',
  templateUrl: './daterange.component.html',
  styleUrls: ['./daterange.component.css']
})
export class DaterangeComponent implements OnInit {

  constructor(
    private _api: ApiService,
    private _router:Router
  ) { }

    dateArray: Array<any> = []

  ngOnInit() {
    // this.onSubmit0
    this.onTest();
  }
  onSubmit(form: NgForm) {
      console.log(form.value);
    this._api.getTypeRequest('data/range/'+form.value.date1+'/'+form.value.date2).subscribe((res: any) => {
      if (res.status) { 
        console.log(res) 
        this.dateArray = res.entries
      } else { 
        console.log(res)
        // alert(res.msg)
      }
    });
  }

  onTest() {
    // this._api.getTypeRequest('data/range/2010-05-01/2020-05-01').subscribe((res: any) => {
    //     if (res.status) { 
    //       console.log(res) 
    //       this.dateArray = res.entries
    //     } else { 
    //       console.log(res)
    //       // alert(res.msg)
    //     }
    //   });
  }
}
