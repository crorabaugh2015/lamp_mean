import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ChatComponent } from '../components/chat/chat.component';
import { CreateComponent } from '../components/create/create.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms'
import { HttpClientModule } from '@angular/common/http';
import { ListComponent } from './list/list.component';

@NgModule({
  declarations: [
    ChatComponent, 
    CreateComponent, ListComponent 
  ],
  imports: [
    CommonModule,
    ReactiveFormsModule,
    FormsModule,
    HttpClientModule
  ],
  exports : [
    ChatComponent, 
    CreateComponent 
  ]
})
export class ChatsModule { }
