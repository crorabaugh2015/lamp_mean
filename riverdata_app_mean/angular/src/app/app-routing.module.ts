import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { DaterangeComponent } from './data/components/daterange/daterange.component';

const routes: Routes = [
  {path: '', component: DaterangeComponent}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
