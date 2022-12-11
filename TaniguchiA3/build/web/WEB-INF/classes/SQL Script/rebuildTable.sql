-- Drop table
drop table if exists Movies;

-- Create table
create table Movies
(
    movieID int not null,
    title varchar(100) not null,
    releaseYear int not null,
    length int not null,
    rating int,
    primary key (movieID)
);

-- Populate table
insert into Movies values(101, 'The Prestige', 2006, 130, 5);
insert into Movies values(102, 'Jojo Rabbit', 2019, 108, 4);
insert into Movies values(103, 'Parasite', 2019, 133, 5);
insert into Movies values(104, 'Paddleton', 2019, 89, 4);
insert into Movies values(105, 'Silence', 2016, 161, 4);
insert into Movies values(106, 'Arrival', 2016, 116, 4);
insert into Movies values(107, 'The Seige of Jadotville', 2016, 108, 3);
insert into Movies values(108, 'The Hateful 8', 2015, 188, 4);
insert into Movies values(109, 'Spotlight', 2015, 129, 4);
insert into Movies values(110, 'My Cousin Vinny', 1992, 120, 4);
insert into Movies values(111, 'Snatch', 2000, 103, 4);
insert into Movies values(112, 'Se7en', 1995, 127, 4);
insert into Movies values(113, 'Eternal Sunshine of the Spotless Mind', 2004, 108, 4);
insert into Movies values(114, 'Fantastic Mr. Fox', 2009, 87, 4);
insert into Movies values(115, 'Shutter Island', 2010, 138, 4);
insert into Movies values(116, "The King's Speech", 2010, 118, 4);
insert into Movies values(117, 'Moneyball', 2011, 134, 4);
insert into Movies values(118, 'Green Room', 2015, 95, 4);
insert into Movies values(119, 'Gone Girl', 2014, 149, 4);
insert into Movies values(120, 'Chef', 2014, 114, 4);