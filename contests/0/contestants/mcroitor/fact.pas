Program Factorial;
var n, i : integer;
    F : longint;
    label 1;
begin
   { Citirea datelor de intrare }
   readln(n);
   { Calcularea factorialului }
   F:=1;
   if n=0 then goto 1;
   for i:=1 to n do F:=F*i;
1: { Scrierea datelor de iesire }
   writeln(F);
end.
