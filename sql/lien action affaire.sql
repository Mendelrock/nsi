update interaction i
inner join interaction p on (p.id_interaction = i.id_interaction_pere) 
set i.id_affaire = p.id_affaire 
where i.id_affaire is null and 
 p.id_affaire is not null