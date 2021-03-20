-- PWS - Play With Santa
-- Made by Madman
-- Another script to spam main
-- Warning! This game contains gore, violence and curse words
-- If you continue to read after this text, no whineing!

-- Version 1.1
-- Added: rightclick menu
-- Added: text to myscore command

-- Version 1.2
-- Fixed: hole problem in miss
-- Added: Comment to score =)

-- Version 1.2.1
-- Fixed: bug in comment system, reported by Cêñoßy†ê

-- Version 1.2.2
-- Changed: Comment's are once again in table format, Thanks to Mutor

-- Version 1.3
-- Fixed: small bug in !helpSanta
-- Added: cmd to run over Santa
-- Changed: moved some code
-- Changed: Scoretable layout, thanks Mutor
-- Added: SortTable, thanks Mutor
-- Added: Option to show how many users in socreboard
-- Changed: myscore code
-- Added: Killsanta cmd

-- Version 1.4
-- Added: Killuser cmd, request by TwîsTèd-dèvîl
-- Added: Protection for hubbot ;)

-- Version 1.4.1
-- Added: Points to Killuser cmd
-- Added: Option to use points for Killuser cmd

-- Thanks to:
-- The >Outland> (some dude on the net, that had posted some of the text used)
-- CrazyGuy, Nillan, Foxy for helping with text's to killSanta
-- Pipen and Busis for helping me code...

tConfig = {
	Bot = "PlayWithSanta",
	ScoreFile = "ScoredSantas.dat",
	UseMenu = 1, -- 1 = on, nil = off
	Menu = "PWS",
	Top = 10, -- How many users to show in scoreboard
	KillUserScore = nil, -- Give points when useing killuser cmd
}

tComment = {
	[1] = {100,"Keep practicing!"}, -- 100 - 200 gives this comment
	[2] = {200,"My granny has higher score"},
	[3] = {300,"Watch out for Santa!"},
	[4] = {400,"Reindeer hunter"},
	[5] = {500,"Lieutenant Santa smasher"},
	[6] = {600,"Could be a Santa killer"},
	[7] = {700,"Watch out Santa.."},
	[8] = {800,"General Santa Killer"},
	[9] = {900,"Santa slaughter!"},
	[10] = {1000,"Get a life!"},
	[11] = {1100,"Who's Santa now!?"}
}

--------------------------------------------------------------------------------------------------
-- End of user settings
--------------------------------------------------------------------------------------------------

function Main()
	if table.maxn == nil then table.maxn = table.getn end
	frmHub:RegBot(tConfig.Bot)
	local file = io.open(tConfig.ScoreFile)
	if file then
		file:close()
	else
		file = io.open(tConfig.ScoreFile,"w+")
		file:write("ScoreTable = {\n}")
		file:close()
	end
	LoadFromFile(tConfig.ScoreFile)
end

function ChatArrival(Killer, data)
	local data = string.sub(data, 1, -2)
	local s,e,cmd = string.find(data,"%b<>%s+[%!%+%#](%S+)")
	if cmd then
		cmd = string.lower(cmd)
		local tCmds = {
			["throwsanta"] = function(Killer, data)
				local Throw = math.random(0,10) -- Desides hit or miss
				local Size = { "big","medium","big","small","big","medium","small","big","medium","big","big","small" }
				local hole = Size[math.random(1,table.maxn(Size))] -- Get hole
				if Throw > 5 then -- If bigger then 5
					Msg = Hit[math.random(1,table.maxn(Hit))] -- Get msg
					Msg = string.gsub(string.gsub(Msg,"%[curuser%]",Killer.sName),"%[hole%]", hole) -- replace [curuser] and [hole]...
					if hole == "big" then -- If hole is big
						score = math.random(1,5) -- Then score is 1 to 5
					elseif hole == "medium" then
						score = math.random(6,10)
					elseif hole == "small" then
						score = math.random(11,15)
					end
					local _,_,point = string.find(Msg,"%[p(%d+)%]") -- Find extra points
					if point then -- If found extra points
						Msg = string.gsub(Msg,"%[p%d+%]",point) -- Get how much points
						score = score + point -- Add it to score
					end
					SaveScore(score,Killer) -- Save scores
					SendToAll(tConfig.Bot, Msg)
					SendToAll(tConfig.Bot, Killer.sName.. " scores " ..score.. " points!")
				else
					Msg = Miss[math.random(1,table.maxn(Miss))]
					Msg = string.gsub(string.gsub(Msg,"%[curuser%]",Killer.sName),"%[hole%]", hole)
					SendToAll(tConfig.Bot, Msg)
				end
			end,
			["runover"] = function(Killer,data)
				LoopTable(Runover,Killer)
			end,
			["killsanta"] = function(Killer,data)
				LoopTable(Kill,Killer)
			end,
			["killuser"] =  function(Killer, data)
				local s,e,vic = string.find(data, "%b<>%s+%S+%s+(%S+)")
				if vic then -- get victim
					if vic == frmHub:GetHubBotName() then -- check if it's hubbot
						Killer:SendData(tConfig.Bot, "Trying to kill the hubbot? Take you punishment!")
						Killer:Disconnect() -- kill user =)
						SendToAll(tConfig.Bot, Killer.sName.. " tried to kill hubbot, and suffered the consequences for it!")
					else
						local vicUser = GetItemByName(vic) -- check if vic is online
						if vicUser then -- vic is online
							local c = math.random(1,table.maxn(KillUser))
							local Msg = KillUser[c][1]
							local score = KillUser[c][2] -- get the score
							Msg = string.gsub(string.gsub(Msg,"%[curuser%]",Killer.sName),"%[vicuser%]",vicUser.sName)
							SendToAll(tConfig.Bot,Msg) -- Send msg
							if tConfig.KillUserScore then
								if KillUser[c][3] then
									SaveScore(KillUser[c][3],vicUser)
									SendToAll(tConfig.Bot, vicUser.sName.. " scores " ..KillUser[c][3].. " points!")
								end
								if score > 0 then -- if score above 0
									SaveScore(score,Killer) -- save score
									SendToAll(tConfig.Bot, Killer.sName.. " scores " ..score.. " points!") -- infrom about score
								end
							end
						else
							Killer:SendData(tConfig.Bot, vic.. " is not online")
						end
					end
				else
					Killer:SendData(tConfig.Bot, "Syntax: !" ..cmd.. " <nick>")
				end
			end,
			["scoreboard"] = function(Killer, data)
				Killer:SendData(tConfig.Bot,SortTable(ScoreTable,tConfig.Top))
			end,
			["myscore"] = function(Killer, data)
				for _,v in ipairs(ScoreTable) do
					if v[2] == Killer.sName then
						Killer:SendData(tConfig.Bot, "Your score is: " ..v[1]) return 1
					end
				end
				Killer:SendData(tConfig.Bot, "No scores fond")
			end,
			["helpSanta"] = function(Killer,data)
				Help = "\r\n\r\nWarning! This game contains gore, violence and curse words\r\n"..
				"Throw Santa by useing !throwSanta\r\n"..
				"If you are lucky, you will hit a hole (big,medium,small)\r\n"..
				"and maybe also score some extra points\r\n"..
				"!scoreboard shows topten players\r\n"..
				"!myscore shows your scores\r\n"..
				"!runover let's you run over Santa...maybe\r\n"..
				"!killsanta Feeling lucky?\r\n"..
				"!killuser <user> try to kill user"
				Killer:SendData(tConfig.Bot, Help)
			end,
		}
		if tCmds[cmd] then
			return tCmds[cmd](Killer, data),1
		end
	end
end

function LoopTable(Table,curUser)
	local c = math.random(1,table.maxn(Table)) -- random a number, 1 to end of table
	local Msg = Table[c][1] -- get msg from table
	local score = Table[c][2] -- get the score
	Msg = string.gsub(Msg,"%[curuser%]",curUser.sName) -- change [curuser] to users name
	SendToAll(tConfig.Bot,Msg) -- Send msg
	if score > 0 then -- if score above 0
		SaveScore(score,curUser) -- save score
		SendToAll(tConfig.Bot, curUser.sName.. " scores " ..score.. " points!") -- infrom about score
	end
end

function SaveScore(score,curUser)
	for i,v in ipairs(ScoreTable) do -- loop table
		if v[2] == curUser.sName then -- if user found in table
			score = score + v[1] -- score is new score + oldscore
			table.remove(ScoreTable,i) -- remove pos
		end
	end
	table.insert(ScoreTable,{score,curUser.sName}) -- insert new score and user
	SaveToFile(tConfig.ScoreFile,ScoreTable,"ScoreTable") -- Save to file
	LoadFromFile(tConfig.ScoreFile) -- load file
end

SortTable = function(Table,Range)
	table.sort(Table, function(a,b) return a[1]>b[1] end)
	if Range then
		local list = "\r\n\r\n\t\t\tTop [ "..Range.." ] Scorers\r\n\t"..
		string.rep("-",110).."\r\n\r\n" 
		for i=1, Range do
			if Table[i] == nil then break else
				list = list.."\t"..i..".\t"..Table[i][1].."\t"..
				string.format("%-35.35s",Table[i][2]).."\t"..
				SetComment(Table[i][1]).."\r\n"
			end
		end
		return list
	end
end

function SetComment(score)
	for i,v in ipairs(tComment) do
		if score < v[1] then
			if tComment[i-1] == nil then
				return "" -- score below 100 gives no comment
			else
				return tComment[i-1][2]
			end
		end
	end
	return tComment[table.maxn(tComment)][2] -- If higher then last entery in tComment, then send last comment in table
end

function NewUserConnected(lazyUser)
	if tConfig.UseMenu and lazyUser.bUserCommand then
		lazyUser:SendData("$UserCommand 1 3 " ..tConfig.Menu.. "\\Throw Santa$<%[mynick]> !throwSanta&#124;")
		lazyUser:SendData("$UserCommand 1 3 " ..tConfig.Menu.. "\\Scoreboard$<%[mynick]> !scoreboard&#124;")
		lazyUser:SendData("$UserCommand 1 3 " ..tConfig.Menu.. "\\My score$<%[mynick]> !myscore&#124;")
		lazyUser:SendData("$UserCommand 1 3 " ..tConfig.Menu.. "\\Run over Santa$<%[mynick]> !runover&#124;")
		lazyUser:SendData("$UserCommand 1 3 " ..tConfig.Menu.. "\\Kill Santa$<%[mynick]> !killsanta&#124;")
		lazyUser:SendData("$UserCommand 1 2 " ..tConfig.Menu.. "\\Kill User$<%[mynick]> !killuser %[nick]&#124;")
		lazyUser:SendData("$UserCommand 1 1 " ..tConfig.Menu.. "\\Kill User$<%[mynick]> !killuser %[line:nick]&#124;")
	end
end

OpConnected = NewUserConnected

------------------------------------------------------
-- Only change below if you know what you are doing --
------------------------------------------------------

KillUser = {
	-- {Msg,curuser score, victim score}, vic score is optional
	[1] = {"[curuser] throws santa at [vicuser]",2},
	[2] = {"Santa lands on [vicuser] making [vicuser] flatt as a pancake, [curuser] laughs evil",2},
	[3] = {"[curuser] straps Santa to a missile and fires them at [vicuser]! BOOM! All that's left of [vicuser] is a bloody spot",4},
	[4] = {"[curuser] catapolts Santa towards [vicuser] killing them instantly",3},
	[5] = {"[curuser] forces [vicuser] and Santa to watch teletubbies! [vicuser]'s brain slowly melts",9},
	[6] = {"Santa gives [curuser] a sword, [curuser] cut's [vicuser] in half! Santa pick up a rocketlauncher and blows [curuser] in to oblivion",1},
	[7] = {"[vicuser] steals Santas slege and runs over Santa and [curuser]",0,2},
	[8] = {"[curuser] tries to force Santa to kill [vicuser], but Santa got and nuke and kills everybody!",0},
	[9] = {"[vicuser] and [curuser] uses an old rusty saw and cuts Santa in to small pieces",math.random(2,4),math.random(1,3)}
}

Runover = {
			--{msg,score},
	[1] = {"[curuser] steals a reindeer and runs over Santa",4},
	[2] = {"Roadkill! Santa got smashed by [curuser]'s tank",5},
	[3] = {"Santa steals [curuser]'s tank and runs over [curuser]",0},
	[4] = {"Santa does a hit and run on [curuser]!",0},
	[5] = {"[curuser] straps Santa to a truck and, then ram the truck in to a wall!",4},
	[6] = {"[curuser] cheats and throw Santa over a cliff",1},
	[7] = {"[curuser] lands a 747 on Santa!",6},
	[8] = {"[curuser] drives a train over Santa. Santa split!",5},
	[9] = {"[curuser] kicks Santa of his sleigh, Santa does a belly flop 1000 feet down!",7},
	[10] = {"[curuser] kicks Santa of his sleigh, Santa does a belly flop 1000 feet down, but bounce back up and make [curuser] do a belly flop straight down to the asphalt",0},
	[11] = {"[curuser] steal Nillan's tractor and, runs over Santa!",4},
}

Kill = {
	[1] = {"[curuser] puts an altimeter bomb in Santas sleigh, so when he goes high enough it's bye-bye for the fuck",5},
	[2] = {"[curuser] loosens the blades on Santas sleigh. When Santa trys to land, it's Santa all over the place",3},
	[3] = {"[curuser] fires a fat-seeking missile at Santa sleigh in mid air. Santa is no more!", 10},
	[4] = {"[curuser] runs over Santa with a car when fatso lands on the road",6},
	[5] = {"[curuser] puts a bear trap in the chimney, Santa falls down and snaps in two",4},
	[6] = {"[curuser] smashes Santa across the face with a metal baseball bat, when he comes down the chimney",5},
	[7] = {"Santa gives [curuser] a grenade as x-mas present",0},
	[8] = {"Feeding Rudolph more cafeine, Santa drives over [curuser]",0},
	[9] = {"Seeing the headlights comming rapidly, [curuser] tries to flee, but ends miserably under Santa's sled",0},
	[10] = {"Santa fall downs the chimney and flattens [curuser]",0},
	[11] = {"After eating cookies and drinking milk, Santa is stuck in  [curuser]'s chimney whilst [curuser] lights up the fire",4},
	[12] = {"After paralizing [curuser] with 6 rounds, Santa shoves his gun up [curuser] to finish the job",0},
	[13] = {"Santa takes out his big whip and beats the living shit out of [curuser]",0},
	[14] = {"[curuser] screams when Santa takes a hand, gets his knife and starts singing 'This little piggy went to a market......'",0},
	[15] = {"[curuser] nukes Santa! Next x-mas Santa will be green. Ops... Looks like no more x-mas...",3},
	[16] = {"After getting hit by [curuser] 's air-to-air missile, Santa crashes on a roof and hangs lifeless over the chimney",5},
	[17] = {"[curuser] picks up a scissor, and stabs Santa 108 times!",9},
	[18] = {"[curuser] reaches for the star on top of the x-mas tree and impale Santa with it",7},
	[19] = {"[curuser] places a spearhead-shaped woodblock in the fireplace, killing Santa instantly when he comes down the chimney",4},
}

Hit = {
	-- [hole], [curuser], [pX] X = nr of points, i.e [p10]
	"How the hell did you get Santa through the [hole] hole [curuser]?",
	"[curuser] kicks Santa in to the [hole] hole!",
	"[curuser] throws Santa in to [hole] hole , but Santa get's stuck, so [curuser] throws a dyniamite and, blows up [hole] hole and Santa! [p15] x-tra points to [curuser]",
	"Santa flys straight through the [hole] hole! [curuser] scores!",
	"Santa get's stuck in the [hole] hole, [curuser] picks up Batman and bashes Santa through the [hole] hole, [p10] extra points to [curuser], for violence against cartoons!",
	"[curuser] put Santa on a quick diet, by using Diet Drain Cleaner, and then throws him through the [hole] hole!",
	"[curuser] rips Santa's hearts, and throws it in the [hole] hole. [p23] points for the gory kill!",
	"Death is summoned by [curuser], Death chops Sant to pieces and throws the pieces in the [hole] hole. [curuser] gets [p13] more points for summoning Death",
	"Showing what a good thrower [curuser] is, [curuser] throws Santa in a loop and then in the [hole] hole.",
	"[curuser] picks up a golf club and swings Santa in to the [hole] hole.",
	"Santa get sucked in to a tornado and drop in to the [hole] hole, [curuser] laughs and scores!",
	"[curuser] use vodoo and make Santa fall in to the [hole] hole.",
	"[curuser] ties Santa to a missile and fires it through the [hole] hole!",
}

Miss = {
	-- [hole], [curuser]
	"[curuser] couldn't even hit grand canyon if [curuser] was standing in it!",
	"[curuser] throws Santa straight at [hole], but suddenly a black hole apperas and sucks Santa in to it, no points for [curuser]",
	"[curuser] missunderstood the game and blew up Santa!",
	"wow! That wasent even close [curuser]!",
	"Santa throws [curuser] in to a brick wall!",
	"Santa bounces on the wall, and flattens [curuser]",
	"[curuser] uses Santa as Piñata!",
	"[curuser] got run over by a reindeer!",
}

------------------------------------------------------
-- Only change above if you know what you are doing --
------------------------------------------------------

function Serialize(tTable, sTableName, sTab)
	assert(tTable, "tTable equals nil");
	assert(sTableName, "sTableName equals nil");
	assert(type(tTable) == "table", "tTable must be a table!");
	assert(type(sTableName) == "string", "sTableName must be a string!");
	sTab = sTab or "";
	sTmp = ""
	sTmp = sTmp..sTab..sTableName.." = {\n"
	for key, value in pairs(tTable) do
		local sKey = (type(key) == "string") and string.format("[%q]",key) or string.format("[%d]",key);
		if(type(value) == "table") then
			sTmp = sTmp..Serialize(value, sKey, sTab.."\t");
		else
			local sValue = (type(value) == "string") and string.format("%q",value) or tostring(value);
			sTmp = sTmp..sTab.."\t"..sKey.." = "..sValue
		end
		sTmp = sTmp..",\n"
	end
	sTmp = sTmp..sTab.."}"
	return sTmp
end

function SaveToFile(file , table , tablename)
	local handle = io.open(file,"w+")
	handle:write(Serialize(table, tablename))
	handle:flush()
	handle:close()
end

function LoadFromFile(filename)
	local f = io.open(filename)
	if f then
		local r = f:read("*a")
		f:flush()
		f:close()
		local func,err = loadstring(r)
		if func then x,err = pcall(func) end
	end
end
