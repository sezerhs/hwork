#include<stdio.h>  
int main(){      
int i=0;    
int marks[25]={43,65,71,56,48,23,22,18,95,5,64,77,92,44,35,24,15,84,7,91,66,78,33,100,21};   

int group09=0;
int group1019=0;
int group2029=0;
int group3039=0;
int group4049=0;
int group5059=0;
int group6069=0;
int group7079=0;
int group8089=0;
int group9099=0;
int group100=0;

for(i=0;i<25;i++){      

	if(marks[i]  <= 9){
		group09++;
		//printf("grup 0-9 %d \n",marks[i]);    
	}
	if(marks[i] > 10 && marks[i] <= 19 ){
		group1019++;    
	}
	if(marks[i] > 20 && marks[i] <= 29 ){
		group2029++;    
	}
	if(marks[i] > 30 && marks[i] <= 39 ){
		group3039++;    
	}
	if(marks[i] > 40 && marks[i] <= 49 ){
		group4049++;    
	}
	if(marks[i] > 50 && marks[i] <= 59 ){
		group5059++;    
	}
	if(marks[i] > 60 && marks[i] <= 69 ){
		group6069++;    
	}
	if(marks[i] > 60 && marks[i] <= 69 ){
		group7079++;    
	}
	if(marks[i] > 80 && marks[i] <= 89 ){
		group8089++;    
	}
	if(marks[i] > 90 && marks[i] <= 99 ){
		group9099++;    
	}
	if(marks[i] == 100){
		group100++;    
	}
}
printf("Grup no           Grup                 Sayi\n");     
printf("1 grup            0-9                  %d \n",group09);
printf("2 grup            10-19                %d \n",group1019);
printf("3 grup            20-29                %d \n",group2029);
printf("4 grup            30-39                %d \n",group3039);
printf("5 grup            40-49                %d \n",group4049);
printf("6 grup            50-59                %d \n",group5059);
printf("7 grup            60-69                %d \n",group6069);
printf("8 grup            70-79                %d \n",group7079);
printf("9 grup            80-89                %d \n",group8089);
printf("10 grup           90-99                %d \n",group9099);
printf("11 grup           100                  %d \n",group100);
return 0;  
}    
