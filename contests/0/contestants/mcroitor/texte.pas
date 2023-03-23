Program Texte;
var N : integer;
    C : char;
    T : string;
    Intrare, Iesire : text;
    L : integer; { lungimea textului T }
    i : integer; { contor penru parcurgerea textului T }
begin
   { Citirea datelor de intrare }
   assign(Intrare, 'texte.in');
   reset(Intrare);
   readln(Intrare, C);
   readln(Intrare, T);
   close(Intrare);
   { Calcularea numarului de aparitii }
   N:=0;
   L:=length(T);
   for i:=1 to L do
      if C=T[i] then N:=N+1;
   { Scrierea datelor de iesire }
   assign(Iesire, 'texte.out');
   rewrite(Iesire);
   writeln(Iesire, N);
   close(Iesire);
end.
